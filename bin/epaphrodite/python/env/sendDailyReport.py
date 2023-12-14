import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
import datetime
import os
import json
import sys

# Function to load SMTP configuration from JSON file
def load_json_config(file_path='smtp.json'):
    with open(file_path) as config_file:
        config_data = json.load(config_file)
    return config_data

# Function to read SMTP configuration from loaded JSON data and environment variables
def read_config_from_json():
    config_data = load_json_config()
    email = os.environ.get('EMAIL')  # Retrieve email from environment variable
    password = os.environ.get('PASSWORD')  # Retrieve password from environment variable
    smtp_server = config_data.get('smtp_server')
    smtp_port = config_data.get('smtp_port')
    
    return email, password, smtp_server, smtp_port

# Function to send an email using SMTP configuration
def send_email(subject, body, to_emails):
    email, password, smtp_server, smtp_port = read_config_from_json()

    msg = MIMEMultipart()
    msg['From'] = email
    msg['To'] = ', '.join(to_emails)
    msg['Subject'] = subject

    msg.attach(MIMEText(body, 'plain'))

    server = smtplib.SMTP(smtp_server, smtp_port)
    server.starttls()
    server.login(email, password)

    for to_email in to_emails:
        server.sendmail(email, to_email, msg.as_string())

    server.quit()

# Function to generate the daily report content
def generate_daily_report():
    today = datetime.date.today()
    report = f"Daily report of {today}: \n\nHello! Here is your report for the day."

    return report

# Function to send the daily report via email to a specified contact
def send_daily_report(subject, contact):
    recipient_emails = [contact]  # Assuming 'contact' contains the email address
    body = generate_daily_report()
    
    send_email(subject, body, recipient_emails)

if __name__ == "__main__":
    # Check if the correct number of arguments is provided
    if len(sys.argv) != 2:
        print("Usage: python sendDailyReport.py <json_values>")
        sys.exit(1)

    json_values = sys.argv[1]  # Retrieve JSON values provided as an argument
    
    try:
        data_sent = json.loads(json_values)  # Load the JSON data
    except json.JSONDecodeError as e:
        print(f"Error in JSON format: {e}")
        sys.exit(1)
    
    # Check if required keys are present in the JSON data
    if 'subject' not in data_sent or 'contact' not in data_sent:
        print("The JSON file must contain 'subject' and 'contact'.")
        sys.exit(1)    
    
    send_daily_report(data_sent['subject'], data_sent['contact'])
