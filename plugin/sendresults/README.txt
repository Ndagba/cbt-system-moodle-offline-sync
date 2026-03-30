📛 Plugin Name:
SendResults
(Local Plugin: local_sendresults)

📌 Purpose:
This plugin allows CBT centers to automatically:

Export quiz results as CSV and JSON

Send them to a central results server

Email the result CSV to a specified email address

Log all activity

Display user-friendly status messages

✅ Features:
Internet connectivity check with retries

Central server integration via API key

Auto CSV file generation with naming convention:
cbt_<servername>_results_<timestamp>.csv

Email results with customizable message body

Admin interface to configure settings

🧩 Requirements:
Moodle 4.4 or compatible

Outgoing mail configured (SMTP setup)

Curl PHP extension enabled

📁 Installation:
Upload the sendresults folder to:

swift
Copy
Edit
/your-site-dir/local/sendresults/
Or zip the folder and install via:
Site administration → Plugins → Install plugins

Complete the installation when prompted.

⚙️ Configuration:
Navigate to:
Site administration → Plugins → Local plugins → SendResults

Set the following:

✅ Server URL — e.g. https://cbtresult.pcnigeria.org.ng/receive.php

✅ API Key — Shared key used to authenticate with central server

✅ Server Name — Identifier (e.g., Minna_center) used in file names

✅ Email Recipient — Where result CSVs will be sent

✅ Email Message Body — Custom message with {servername} placeholder

📤 Usage:
Open a course where a quiz has been conducted.

Click on the "Send Results" option under:

The quiz activity submenu

Or course settings submenu

The plugin will:

Check for internet access

Export results

Send CSV + JSON to server

Send the CSV via email

Display success or error messages on screen

🧪 Troubleshooting:
Internet not detected?
Check firewall or network blocks on port 53 or try Google DNS (8.8.8.8)

Email not sent?
Confirm your SMTP settings under:
Site administration → Server → Outgoing mail configuration

Nothing happens on click?
Check file permissions in local/sendresults, enable debugging, and review PHP error logs.

👷 Credits:
Developed for nationwide CBT coordination using Moodle in Nigeria.
Maintained by [Gana David/Lusaworks].