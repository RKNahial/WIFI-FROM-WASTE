import time
import os
import cv2
from ultralytics import YOLO
from picamera2 import Picamera2
import RPi.GPIO as GPIO

def check_camera():
    # Implementation of check_camera function
    return True  # Placeholder return, actual implementation needed

def angle_to_duty_cycle(angle):
    """Convert angle (0-180) to duty cycle (2-12)"""
    return angle / 18 + 2

def set_servo_angle(angle):
    """Set servo to specific angle with 1 second delay"""
    duty = angle / 18 + 2
    GPIO.output(MAIN_SERVO_PIN, True)
    main_pwm.ChangeDutyCycle(duty)
    time.sleep(1)
    GPIO.output(MAIN_SERVO_PIN, False)
    main_pwm.ChangeDutyCycle(0)

def set_plastic_servo_angle(angle):
    """Set plastic detection servo to specific angle with 1 second delay"""
    duty = angle / 18 + 2
    GPIO.output(PLASTIC_SERVO_PIN, True)
    plastic_pwm.ChangeDutyCycle(duty)
    time.sleep(1)
    GPIO.output(PLASTIC_SERVO_PIN, False)
    plastic_pwm.ChangeDutyCycle(0)

def quick_servo_sequence(pwm, start_angle, end_angle):
    """Quickly move servo from start to end angle"""
    set_servo_angle(start_angle)
    time.sleep(0.1)  # Short delay between movements
    set_servo_angle(end_angle)

def real_time_detection():
    cap = None
    # State tracking variables
    current_state = "WAITING_FIRST"  # States: WAITING_FIRST, WAITING_SECOND
    last_detected_class = None
    detection_start_time = None
    timeout_duration = 10  # Timeout in seconds
    
    try:
        # Camera initialization
        print("\nChecking camera system...")
        if not check_camera():
            raise RuntimeError("Camera hardware not detected")

        cap = cv2.VideoCapture(0)  # Open laptop camera
        
        # Set a wider resolution for more zoomed out view
        cap.set(cv2.CAP_PROP_FRAME_WIDTH, 1280)  # Increased from 640 to 1280
        cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 720)  # Increased from 480 to 720
        
        # Try to set zoom to minimum (if supported by camera)
        cap.set(cv2.CAP_PROP_ZOOM, 0)  # 0 is usually the widest zoom
        
        # Print camera properties
        width = cap.get(cv2.CAP_PROP_FRAME_WIDTH)
        height = cap.get(cv2.CAP_PROP_FRAME_HEIGHT)
        print(f"\nCamera resolution set to: {width}x{height}")
        
        model_path = '/home/pi/Documents/Yolo_model_using_camera/best.pt'
        if not os.path.exists(model_path):
            raise FileNotFoundError(f"Model file not found at {model_path}")
        model = YOLO(model_path)
        
        # Set initial servo positions
        set_servo_angle(0)      # Main sorting servo starts at 0 degrees
        set_plastic_servo_angle(45)  # Plastic detection servo starts at 45 degrees
        print("Servos initialized to starting positions")
        
        while True:
            ret, frame = cap.read()
            if not ret:
                print("Failed to grab frame")
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
                        
                        # Activate plastic bottle servo if plastic detected
                        if cls == 0:  # Plastic bottle
                            print("Plastic bottle detected! Activating plastic bottle servo.")
                            try:
                                set_plastic_servo_angle(150)  # Move to 150 degrees
                                time.sleep(1)
                                set_plastic_servo_angle(45)   # Return to 45 degrees
                            except Exception as e:
                                print(f"Plastic servo error: {e}")
                        
                        # State machine logic for main sorting servo
                        if current_state == "WAITING_FIRST":
                            last_detected_class = class_name
                            current_state = "WAITING_SECOND"
                            detection_start_time = time.time()
                            message = f"{class_name} detected - Place a different object"
                            # Move main servo to initial position when first object detected
                            try:
                                set_servo_angle(0)  # Changed from 90 to 0 degrees
                                print("Main servo moved to initial position")
                            except Exception as e:
                                print(f"Main servo error: {e}")
                        
                        elif current_state == "WAITING_SECOND":
                            if time.time() - detection_start_time > timeout_duration:
                                current_state = "WAITING_FIRST"
                                last_detected_class = None
                                message = "Timeout - Starting over"
                                # Return main servo to starting position on timeout
                                try:
                                    set_servo_angle(0)
                                    print("Main servo returned to start - timeout")
                                except Exception as e:
                                    print(f"Main servo error: {e}")
                            elif class_name == last_detected_class:
                                message = "Need a different object!"
                            else:
                                message = "Valid sequence! Starting over..."
                                current_state = "WAITING_FIRST"
                                last_detected_class = None
                                # Move main servo for valid sequence
                                try:
                                    set_servo_angle(90)  # Open the servo
                                    time.sleep(1)        # Wait 1 second
                                    set_servo_angle(0)   # Close the servo
                                    print("Main servo completed open-close sequence")
                                except Exception as e:
                                    print(f"Main servo error: {e}")
            except Exception as e:
                print(f"Prediction error: {e}")
            
            # Display the frame with status message
            cv2.imshow("Status", display_frame)
            if cv2.waitKey(1) & 0xFF == ord('q'):
                break
    except Exception as e:
        print(f"Main function error: {e}")
    finally:
        if cap:
            cap.release()
        cv2.destroyAllWindows()

if __name__ == "__main__":
    real_time_detection() 