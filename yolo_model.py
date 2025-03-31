from picamera2 import Picamera2, Preview
import cv2
import time
from ultralytics import YOLO
import numpy as np
import os
import RPi.GPIO as GPIO
import requests

# Servo setup
SERVO_PIN = 23
GPIO.setmode(GPIO.BCM)
GPIO.setup(SERVO_PIN, GPIO.OUT)
pwm = GPIO.PWM(SERVO_PIN, 50)  # 50Hz frequency
pwm.start(0)

API_ENDPOINT = "http://192.168.254.174:8000/api/material-detection"

def set_servo_angle(angle):
    duty = angle / 18 + 2
    GPIO.output(SERVO_PIN, True)
    pwm.ChangeDutyCycle(duty)
    time.sleep(1)
    GPIO.output(SERVO_PIN, False)
    pwm.ChangeDutyCycle(0)

def check_camera():
    try:
        print("\nDiagnostic Information:")
        print("----------------------")
        
        # Check for video devices
        try:
            devices = os.listdir('/dev')
            video_devices = [d for d in devices if d.startswith('video')]
            print("Video devices found:", video_devices)
        except Exception as e:
            print("Could not check video devices:", e)
            
        # Try to get camera info
        cameras = Picamera2.global_camera_info()
        print(f"Picamera2 detected cameras: {len(cameras)}")
        
        if len(cameras) == 0:
            print("\nNo cameras detected by Picamera2!")
            print("\nFor Raspberry Pi 5:")
            print("1. Ensure camera is properly connected")
            print("2. Check if legacy camera support is enabled in raspi-config")
            print("3. Verify camera ribbon cable orientation")
            print("4. Try rebooting the system")
            return False
            
        for i, cam in enumerate(cameras):
            print(f"\nCamera {i} details:")
            for key, value in cam.items():
                print(f"  {key}: {value}")
                
        return True
    except Exception as e:
        print(f"Error during camera check: {e}")
        print("Camera subsystem error - please check hardware connection")
        return False

def detect_materials():
    try:
        # Initialize camera
        picam2 = Picamera2()
        preview_config = picam2.create_preview_configuration(
            main={"size": (640, 480), "format": "BGR888"}
        )
        picam2.configure(preview_config)
        picam2.start()
        
        # Load YOLO model
        model_path = '/home/pi/Documents/Yolo_model_using_camera/best.pt'
        model = YOLO(model_path)
        
        # Initialize detection counts
        detections = {
            'plastic': 0,
            'can': 0
        }
        
        # Capture and process frame
        frame = picam2.capture_array()
        print("\nProcessing frame for detections...")
        results = model.predict(source=frame, conf=0.60, iou=0.45)
        print(f"YOLO model predictions: {len(results)} objects detected")
        
        if len(results) > 0:
            result = results[0]
            boxes = result.boxes
            
            for box in boxes:
                cls = int(box.cls[0])
                conf = float(box.conf[0])
                
                # Map class index to type (assuming 0=plastic, 1=can)
                material_type = "plastic" if cls == 0 else "can"
                detections[material_type] += 1
        
        # Send data for each material type with detections
        for material_type, count in detections.items():
            if count > 0:
                print(f"\nSending detection data:")
                print(f"Material Type: {material_type}")
                print(f"Count: {count}")
                
                data = {
                    "material_type": material_type,
                    "count": count
                }
                
                try:
                    print(f"Sending request to: {API_ENDPOINT}")
                    print(f"Request data: {data}")
                    response = requests.post(
                        API_ENDPOINT, 
                        json=data,
                        timeout=5,
                        headers={'Content-Type': 'application/json'}
                    )
                    print(f"Full response: {response.text}")
                except requests.exceptions.ConnectionError as e:
                    print(f"Connection Error: Please check if the server is running at {API_ENDPOINT}")
                    print(f"Detailed error: {str(e)}")
                except requests.exceptions.Timeout:
                    print("Request timed out. Server might be slow or unreachable")
                except Exception as e:
                    print(f"Error sending data to API: {e}")
        
        picam2.stop()
        
    except Exception as e:
        print(f"Error in detection: {e}")

def real_time_detection():
    picam2 = None
    # State tracking variables
    current_state = "WAITING_FIRST"  # States: WAITING_FIRST, WAITING_SECOND
    last_detected_class = None
    detection_start_time = None
    timeout_duration = 10  # Timeout in seconds
    
    try:
        # Camera initialization code remains the same
        print("\nChecking camera system...")
        if not check_camera():
            raise RuntimeError("Camera hardware not detected")

        picam2 = Picamera2(0)
        preview_config = picam2.create_preview_configuration(
            main={"size": (640, 480), "format": "BGR888"}
        )
        picam2.configure(preview_config)
        picam2.start()
        time.sleep(2)
        
        model_path = '/home/pi/Documents/Yolo_model_using_camera/best.pt'
        if not os.path.exists(model_path):
            raise FileNotFoundError(f"Model file not found at {model_path}")
        model = YOLO(model_path)
        
        # Set initial servo position
        set_servo_angle(0)
        print("Servo initialized to starting position")
        
        while True:
            frame = picam2.capture_array()
            if frame is None:
                continue
            
            # Create a copy of the frame for status display
            display_frame = frame.copy()
            
            # Add status message at the top
            status_msg = f"Current State: {current_state}"
            cv2.putText(display_frame, status_msg, (10, 30), 
                      cv2.FONT_HERSHEY_SIMPLEX, 0.7, (255, 255, 255), 2)
            
            try:
                results = model.predict(source=frame, conf=0.60, iou=0.45)
                
                if len(results) > 0:
                    result = results[0]
                    boxes = result.boxes
                    
                    for box in boxes:
                        x1, y1, x2, y2 = box.xyxy[0].cpu().numpy()
                        x1, y1, x2, y2 = int(x1), int(y1), int(x2), int(y2)
                        cls = int(box.cls[0])
                        conf = float(box.conf[0])
                        
                        # Draw rectangle
                        cv2.rectangle(display_frame, (x1, y1), (x2, y2), (0, 255, 0), 2)
                        
                        # Get class name
                        class_names = {0: "Plastic Bottle", 1: "Can"}
                        class_name = class_names.get(cls, f"Class {cls}")
                        
                        # State machine logic
                        if current_state == "WAITING_FIRST":
                            last_detected_class = class_name
                            current_state = "WAITING_SECOND"
                            detection_start_time = time.time()
                            message = f"{class_name} detected - Place a different object"
                            # Move servo to initial position when first object detected
                            try:
                                set_servo_angle(90)
                                print("Servo moved to initial position")
                            except Exception as e:
                                print(f"Servo error: {e}")
                        
                        elif current_state == "WAITING_SECOND":
                            if time.time() - detection_start_time > timeout_duration:
                                current_state = "WAITING_FIRST"
                                last_detected_class = None
                                message = "Timeout - Starting over"
                                # Return servo to starting position on timeout
                                try:
                                    set_servo_angle(0)
                                    print("Servo returned to start - timeout")
                                except Exception as e:
                                    print(f"Servo error: {e}")
                            elif class_name == last_detected_class:
                                message = "Need a different object!"
                            else:
                                message = "Valid sequence! Starting over..."
                                current_state = "WAITING_FIRST"
                                last_detected_class = None
                                # Move servo for valid sequence
                                try:
                                    set_servo_angle(180)  # Open fully
                                    time.sleep(2)         # Keep open for 2 seconds
                                    set_servo_angle(0)    # Return to start
                                    print("Servo completed valid sequence movement")
                                except Exception as e:
                                    print(f"Servo error: {e}")
                        
                        # Display object label and message
                        label = f"{class_name}: {conf:.2f}"
                        cv2.putText(display_frame, label, (x1, y1-10), 
                                  cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 255, 0), 2)
                        cv2.putText(display_frame, message, (x1, y1-30), 
                                  cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 0, 255), 2)
                
                # Display countdown if waiting for second object
                if current_state == "WAITING_SECOND" and detection_start_time is not None:
                    remaining = timeout_duration - int(time.time() - detection_start_time)
                    if remaining > 0:
                        countdown = f"Time remaining: {remaining}s"
                        cv2.putText(display_frame, countdown, (10, 60), 
                                  cv2.FONT_HERSHEY_SIMPLEX, 0.7, (0, 255, 255), 2)
                
                # After detection of class and confidence
                if cls in [0, 1]:  # 0 for plastic, 1 for can
                    material_type = "plastic" if cls == 0 else "can"
                    print(f"\nSending detection data:")
                    print(f"Material Type: {material_type}")
                    print(f"Count: 1")  # Each detection counts as 1
                    
                    data = {
                        "material_type": material_type,
                        "count": 1
                    }
                    
                    try:
                        print(f"Sending request to: {API_ENDPOINT}")
                        print(f"Request data: {data}")
                        response = requests.post(
                            API_ENDPOINT, 
                            json=data,
                            timeout=5,
                            headers={'Content-Type': 'application/json'}
                        )
                        print(f"Full response: {response.text}")
                    except requests.exceptions.ConnectionError as e:
                        print(f"Connection Error: Please check if the server is running at {API_ENDPOINT}")
                        print(f"Detailed error: {str(e)}")
                    except requests.exceptions.Timeout:
                        print("Request timed out. Server might be slow or unreachable")
                    except Exception as e:
                        print(f"Error sending data to API: {e}")
                
            except Exception as e:
                print(f"Error during detection: {str(e)}")
                continue
            
            # Display frame
            cv2.imshow("Detection", display_frame)
            
            if cv2.waitKey(1) & 0xFF == ord('q'):
                break
            
            time.sleep(0.01)
            
    except KeyboardInterrupt:
        print("Detection stopped by user")
    finally:
        if picam2 is not None:
            picam2.stop()
        cv2.destroyAllWindows()
        pwm.stop()
        GPIO.cleanup()

def debug_camera():
    print("\nCamera Debug Information:")
    print("-----------------------")
    
    # Check video devices
    print("\nChecking video devices:")
    try:
        devices = os.listdir('/dev')
        video_devices = [d for d in devices if d.startswith('video')]
        print(f"Video devices found: {video_devices}")
    except Exception as e:
        print(f"Error checking video devices: {e}")
    
    # Check camera info
    print("\nChecking camera information:")
    try:
        cameras = Picamera2.global_camera_info()
        print(f"Number of cameras detected: {len(cameras)}")
        print(f"Camera info: {cameras}")
    except Exception as e:
        print(f"Error getting camera info: {e}")
    
    # Try to initialize camera
    print("\nTrying to initialize camera:")
    try:
        picam2 = Picamera2(0)  # Explicitly try camera 0
        print("Camera initialization successful")
        
        config = picam2.create_preview_configuration()
        picam2.configure(config)
        print("Camera configuration successful")
        
        picam2.start()
        print("Camera started successfully")
        
        time.sleep(2)
        picam2.capture_file("test.jpg")
        print("Test image captured")
        
        picam2.close()
        print("Camera closed successfully")
        
    except Exception as e:
        print(f"Error during camera initialization: {e}")
        print("\nTroubleshooting suggestions:")
        print("1. Check physical connection")
        print("2. Verify ribbon cable orientation (blue side faces USB ports on Pi 5)")
        print("3. Try reseating the camera cable")
        print("4. Ensure camera module isn't damaged")

if __name__ == "__main__":
    real_time_detection()

