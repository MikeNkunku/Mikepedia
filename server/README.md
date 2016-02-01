# Mikepedia

I suppose the PHP server is already installed.
For the database, I use PostgreSQL.

## Install Phalcon under Linux
```
sudo apt-get install php5-dev libpcre3-dev gcc make
git clone --depth=1 git://github.com/phalcon/cphalcon.git
cd cphalcon/build
sudo ./install
```
**NB**: If the installation fails, just assign at least 1GB to PHP server's memory.

Check the installation:
```
php -a
get_loaded_extensions()
exit
```
*phalcon* should be among the extensions listed.
