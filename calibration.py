import time
from hx711 import HX711

# Initialize HX711
print("Setting up HX711...")
hx = HX711(DOUT_PIN, SCK_PIN)  # Changed from keyword arguments to positional arguments

print("Setting gain...")
hx.set_gain(128)
time.sleep(0.5) 