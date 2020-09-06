rm -rf ./email.neon
echo "
mail:
	# use SmtpMailer
	smtp: true       
	host: 'smtp.gmail.com'         
	username: 'your_email_here'    
	password: 'your_email_password'   
	timeout: 60    
	secure: 'tls'
" > email.neon
rm -rf ./apache/certs
rm -rf ./mariadb/data
rm -rf ./server/node_modules
rm -rf ./server/vendor
rm -rf ./server/www/dist/*.js
rm -rf ./server/www/dist/*.wasm
rm -rf ./server/composer.lock
rm -rf ./server/package-lock.json