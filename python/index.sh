#!/bin/bash

# Activate Conda
source /home/htsaxon/anaconda3/etc/profile.d/conda.sh  # or your conda path
conda activate htsaxon

# Navigate to app directory
cd /var/www/hts/python

# Start Gunicorn
gunicorn --bind 127.0.0.1:5050 app:app
