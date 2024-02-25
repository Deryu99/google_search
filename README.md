<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="container">
  <h1>Server Setup and Moodle Installation Guide</h1>
  <ol>
    <li>
        <h2>Server Setup and Configuration:</h2>
        <ul>
            <li>
                <p>Get $50 (or more) hosting credit from DigitalOcean and create a Linux VM:</p>
                <p>Visit the DigitalOcean website and sign up for an account. Navigate to the billing section and claim the student credit if eligible. Create a new droplet (VM) with your preferred Linux distribution (e.g., Ubuntu). Follow the on-screen instructions to complete the creation process.</p>
            </li>
            <li>
                <p>Provide the VM's IP to register a DNS name for your server:</p>
                <p>Once your VM is created, note down its IP address. You can register a DNS name for your server using services like DigitalOcean's Domain Name System (DNS) management or any other DNS provider of your choice. Point the DNS record to your VM's IP address.</p>
            </li>
            <li>
                <p>Install SSH public keys on the VM for secure access:</p>
                <p>Generate SSH keys on your local machine if you haven't already done so. Use the following command to generate SSH keys:</p>
                <pre><code>ssh-keygen -t rsa -b 4096</code></pre>
              -t rsa: Specifies the type of key to create. In this case, it specifies that the key type will be RSA, which is a widely used public-key cryptosystem. -b 4096: Specifies the number of bits in the key. 
                <p>Copy the SSH public key to your VM by running the following command, replacing <code>your_username</code> and <code>your_vm_ip</code> with your actual username and VM's IP address:</p>
                <pre><code>ssh-copy-id your_username@your_vm_ip</code></pre>
                <p>You will be prompted to enter your VM's password. Once the SSH key is copied, you can log in to your VM securely without entering a password.</p>
            </li>
            <li>
                <p>Optionally, install fail2ban for SSH security:</p>
                <p>Install fail2ban on your VM to enhance SSH security. Use the package manager of your Linux distribution to install fail2ban. For example, on Ubuntu, you can use the following command:</p>
                <pre><code>sudo apt update && sudo apt install fail2ban</code></pre>
                <p>Configure fail2ban to monitor SSH login attempts and ban IP addresses that exhibit suspicious behavior.</p>
            </li>
        </ul>
    </li>
<li>
    <h2>Web Server Setup (Nginx):</h2>
    <ul>
        <li>Install Nginx:</li>
        <p>
            Use the package manager of your Linux distribution to install Nginx. For example, on Ubuntu, you can use the following command:
            <pre><code>sudo apt update && sudo apt install nginx</code></pre>
        </p>
        <li>Start and enable Nginx:</li>
        <p>
            Once Nginx is installed, start the Nginx service using the following command:
            <pre><code>sudo systemctl start nginx</code></pre>
            Enable Nginx to start automatically on system boot:
            <pre><code>sudo systemctl enable nginx</code></pre>
        </p>
        <li>Configure Nginx for Moodle:</li>
        <p>
            Create a server block configuration file for Moodle in Nginx's sites-available directory. This file will define how Nginx should handle requests for your Moodle site.
            <pre><code>sudo nano /etc/nginx/sites-available/moodle</code></pre>
            Add the following configuration (adjust it according to your setup):
            <pre><code>
            server {
                listen 80;
                server_name your_domain.com;
                root /var/www/html/moodle;
                index index.php index.html index.htm;
                location / {
                    try_files $uri $uri/ /index.php?$query_string;
                }
                location ~ \.php$ {
                    include snippets/fastcgi-php.conf;
                    fastcgi_pass unix:/run/php/php7.4-fpm.sock;
                    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
                    include fastcgi_params;
                }
                location ~ /\.ht {
                    deny all;
                }
            }
            </code></pre>
            Save the file and create a symbolic link to enable the site:
            <pre><code>sudo ln -s /etc/nginx/sites-available/moodle /etc/nginx/sites-enabled/</code></pre>
            Test the Nginx configuration for any syntax errors:
            <pre><code>sudo nginx -t</code></pre>
            If the test is successful, reload Nginx to apply the changes:
            <pre><code>sudo systemctl reload nginx</code></pre>
        </p>
    </ul>
</li>
    <li>
    <h2>Database Setup (PostgreSQL):</h2>
    <ul>
        <li>Install PostgreSQL:</li>
        <p>
            Use the package manager of your Linux distribution to install PostgreSQL. For example, on Ubuntu, you can use the following command:
            <pre><code>sudo apt update && sudo apt install postgresql postgresql-contrib</code></pre>
        </p>
        <li>Create a PostgreSQL user and database for Moodle:</li>
        <p>
            Log in to the PostgreSQL database server as the default system user `postgres`:
            <pre><code>sudo -u postgres psql</code></pre>
            Create a new PostgreSQL user for Moodle (replace `moodleuser` and `password` with your desired username and password):
            <pre><code>CREATE USER moodleuser WITH PASSWORD 'password';</code></pre>
            Create a new PostgreSQL database for Moodle and grant all privileges to the Moodle user:
            <pre><code>CREATE DATABASE moodle OWNER moodleuser;</code></pre>
            Exit the PostgreSQL prompt:
            <pre><code>\q</code></pre>
        </p>
    </ul>
</li>
<li>
    <h2>PHP Setup:</h2>
    <ul>
        <li>Install PHP and necessary extensions for Moodle:</li>
        <p>
            Install PHP and required PHP extensions for Moodle to function properly. Use the package manager of your Linux distribution to install PHP and necessary extensions. For example, on Ubuntu, you can use the following command:
            <pre><code>sudo apt install php php-cli php-fpm php-pgsql php-common php-mbstring php-xmlrpc php-soap php-gd php-xml php-intl php-zip php-curl php-ldap php-opcache</code></pre>
        </p>
    </ul>
</li>
<li>
    <h2>SSL/TLS Certificate Setup:</h2>
    <ul>
        <li>Secure access with HTTPS:</li>
        <p>
            Secure access to your Moodle site by enabling HTTPS. This requires obtaining an SSL/TLS certificate.
        </p>
        <li>Use Let's Encrypt for a free SSL certificate:</li>
        <p>
            Let's Encrypt provides free SSL/TLS certificates that are trusted by most modern web browsers. Install Certbot, the Let's Encrypt client, on your server. Use Certbot to obtain and install the SSL certificate for your domain. The exact commands may vary depending on your server configuration, but typically, you can use the following command:
            <pre><code>sudo apt install certbot python3-certbot-nginx</code></pre>
            After installing Certbot, run the following command to obtain and install the SSL certificate for your domain:
            <pre><code>sudo certbot --nginx</code></pre>
            Follow the on-screen instructions to complete the certificate installation process.
        </p>
    </ul>
</li>
    <li>
    <h2>Moodle Installation:</h2>
    <ul>
        <li>Download Moodle and extract the files:</li>
        <p>
            Visit the Moodle website and download the latest version of Moodle. You can download it as a compressed archive file (e.g., .zip or .tar.gz). Once downloaded, extract the contents of the archive to a temporary location on your server.
        </p>
        <li>Move Moodle files to the appropriate directory:</li>
        <p>
            Move the extracted Moodle files to the directory where you want to host Moodle on your server. For example, if you want to host Moodle in the `/var/www/html` directory, you can use the following command:
            <pre><code>sudo mv /path/to/moodle /var/www/html</code></pre>
            Replace `/path/to/moodle` with the actual path where you extracted the Moodle files.
        </p>
        <li>Set permissions for the Moodle directory:</li>
        <p>
            Set appropriate permissions for the Moodle directory to ensure that the web server can read and write files as needed. Use the following command to set permissions:
            <pre><code>sudo chown -R www-data:www-data /var/www/html/moodle</code></pre>
            This command sets the ownership of the Moodle directory to the web server user and group.
        </p>
        <li>Configure the web server (Nginx) for Moodle:</li>
        <p>
            Configure Nginx to serve Moodle by creating a server block configuration file for Moodle. Follow the instructions provided earlier for configuring Nginx for Moodle.
        </p>
        <li>Access the Moodle site and complete the installation:</li>
        <p>
            Open a web browser and navigate to your Moodle site using its domain name or IP address. You should see the Moodle installation wizard. Follow the on-screen instructions to complete the installation process, including setting up the database connection using the PostgreSQL credentials you created earlier.
        </p>
    </ul>
    </li>
    <li>
    <h2>Moodle Configuration:</h2>
    <ul>
        <li>
            <p>Start the Moodle installation by visiting the website.</p>
        </li>
        <li>
            <p>Configure the database connection using the PostgreSQL credentials.</p>
        </li>
        <li>
            <p>Follow the installation wizard to configure Moodle.</p>
        </li>
        <li>
            <p>Customize Moodle settings such as site settings, authentication methods, plugins, and themes.</p>
        </li>
    </ul>
    </li>
    <li>
        <h2>Moodle Block Development:</h2>
        <ul>
            <li>
                <p>Create necessary files for the Moodle block:</p>
                <p>Start by creating the main PHP file for your Moodle block, e.g., <code>block_google_search.php</code>. This file will define the block and its functionality.</p>
                <pre><code>&lt;?php
// PHP code for defining Moodle block
// Example:
class block_google_search extends block_base {
    // Block implementation
}
?&gt;</code></pre>
                <p>Additionally, create an <code>edit_form.php</code> file for editing block settings if needed.</p>
            </li>
            <li>
                <p>Implement the Google Custom Search API:</p>
                <p>Integrate the Google Custom Search API into your Moodle block to fetch search results. You'll need to make HTTP requests to the API and process the JSON response.</p>
                <pre><code>// PHP code example for API integration
$response = file_get_contents('https://www.googleapis.com/customsearch/v1?key=YOUR_API_KEY&amp;cx=YOUR_CUSTOM_SEARCH_ENGINE_ID&amp;q=SEARCH_QUERY');
$data = json_decode($response);
// Process data and display results
</code></pre>
            </li>
            <li>
                <p>Enhance the display of search results using HTML and CSS:</p>
                <p>Format the fetched search results and style them using HTML and CSS to improve their presentation within the Moodle block.</p>
                <pre><code>&lt;?php foreach ($data-&gt;items as $item): ?&gt;
&lt;div class="search-result"&gt;
    &lt;a href="&lt;?php echo $item-&gt;link; ?&gt;"&gt;&lt;?php echo $item-&gt;title; ?&gt;&lt;/a&gt;
    &lt;p&gt;&lt;?php echo $item-&gt;snippet; ?&gt;&lt;/p&gt;
&lt;/div&gt;
&lt;?php endforeach; ?&gt;
</code></pre>
            </li>
        </ul>
    </li>
    <li>
        <h2>GitHub Repository Setup:</h2>
        <ul>
            <li>
                <p>Publish your Moodle block code to a Git repository on GitHub:</p>
                <p>Create a new repository on GitHub and push your Moodle block code to it. Make sure to include all necessary files and directories.</p>
                <pre><code># Example commands to publish code to GitHub
git init
git add .
git commit -m "Initial commit"
git remote add origin https://github.com/your-username/your-repository.git
git push -u origin master
</code></pre>
            </li>
        </ul>
    </li>
  </ol>
</div>
</body>
</html>
