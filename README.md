# tregmine-web
This is the repository for the Tregmine Web Interface; It is available under the MIT License. Support is not guaranteed and should not be expected. If you need help, open a ticket in the issues section and you may get help. Again, you are pretty much on your own and opening a ticket in the issues section is a hit or miss.
# Setup
Before deploying the web interface, make sure you go through all files in the project and personalize variables such as URLs and PayPal information.
 * All files in the /include directory MUST be configured in order for the website to function at all.
 * Some templates, pages, and CSS files refer to tregmine.com or its subdomains. I recommend searching for tregmine.com in the project and replacing it with your own URLs.
 * Some features, such as the chat feature, has been removed from the Tregmine plugin itself and therefor directs to Discord. You can add the code back to the plugin and re-enable the chat interface or you can simply use Discord.
# Database pre-requisites
For full compatibility, ensure your database has the following:
 * A Tregmine database installed with the latest tregminedb.sql export on the Tregmine repository
 * A PayPal account which is capable of using the PayPal API as well as making / receiving transactions.
 * Some degree of knowledge on how to use PHP

# Credit
Full credit goes to the original creators of the interface:
 * Emil Hernvall
 * lDiverse
 * George Bombadil
