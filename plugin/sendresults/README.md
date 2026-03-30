# 📤 SendResults (Moodle Local Plugin)

🚀 A custom Moodle plugin for exporting CBT results and synchronizing them with a central server — designed for large-scale, low-connectivity environments.

---

## 🎯 Overview

SendResults enables CBT centers to:

- Export quiz results as **CSV and JSON**
- Send results to a **central server**
- Email result files automatically
- Handle unreliable internet with retry logic
- Provide clear user feedback during the process

---

## 🌍 Real-World Usage

This plugin was used as part of a nationwide CBT system to conduct **Pharmacy Technician exams across all 36 states in Nigeria**, ensuring reliable result collection despite unstable internet conditions.

---

## ✨ Features

- 🌐 Internet connectivity check with retry mechanism  
- 🔐 Secure API integration using API key  
- 📄 Automatic CSV generation with naming format:  
  `cbt_<servername>_results_<timestamp>.csv`  
- 📤 JSON + CSV transmission to central server  
- 📧 Email delivery with customizable message  
- ⚙️ Admin configuration panel  
- 🧾 Activity logging and status feedback  

---

## 🧩 Requirements

- Moodle 4.4 or compatible  
- PHP cURL extension enabled  
- SMTP configured for outgoing mail  

---

## 📦 Installation

### Option 1: Manual Installation

Upload the plugin to: /your-moodle-dir/local/sendresults/


### Option 2: ZIP Upload

- Navigate to:  
  **Site administration → Plugins → Install plugins**
- Upload the plugin ZIP file  
- Complete installation via the Moodle interface  

---

## ⚙️ Configuration

Go to:

**Site administration → Plugins → Local plugins → SendResults**

Configure the following:

- **Server URL**  
  Example: `https://cbtresult.pcnigeria.org.ng/receive.php`

- **API Key**  
  Shared key for authentication with central server  

- **Server Name**  
  Unique identifier (e.g. `Minna_center`) used in file naming  

- **Email Recipient**  
  Address to receive result CSV files  

- **Email Message Body**  
  Supports placeholder: `{servername}`  

---

## 📤 Usage

1. Navigate to a course with a completed quiz  
2. Click **Send Results** from:
   - Quiz activity submenu  
   - Course settings submenu  

### The plugin will:

- Check internet connectivity  
- Generate CSV and JSON results  
- Send data to central server  
- Email the CSV file  
- Display success or error messages  

---

## 🧪 Troubleshooting

### ❌ Internet not detected
- Check firewall or DNS settings  
- Try using Google DNS: `8.8.8.8`

---

### ❌ Email not sent
- Verify SMTP configuration:  
  **Site administration → Server → Outgoing mail configuration**

---

### ❌ No response on action
- Check file permissions in `/local/sendresults/`  
- Enable Moodle debugging  
- Review PHP error logs  

---

## 🧠 Architecture Summary

- Moodle (Local CBT Server)
- SendResults Plugin (Processing Layer)
- Central Server (Aggregation)
- Email System (Delivery)

---

## 👷 Author

Developed by **Gana David (Lusaworks)**  
Focused on scalable CBT and education systems in low-connectivity environments.

---

## 📬 Contact

- LinkedIn: https://www.linkedin.com/in/lusasms/  
- Email: ndagba4me@gmail.com  

---

🚀 *Open to collaboration and production deployments*
