#!/bin/bash

# sudo nano /etc/systemd/system/fuelpump-ai.service

# Activate Conda
source /home/htsaxon/anaconda3/etc/profile.d/conda.sh  # or your conda path
conda activate htsaxon

# Navigate to app directory
cd /var/www/fuelpump/python

# Start Gunicorn
gunicorn --bind 127.0.0.1:5060 app:app
