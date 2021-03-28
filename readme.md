
**Setting up your development environment on your local machine :**

## 1. Clone the repository
<ul>
<li> git clone https://github.com/asimbilaladil/comic-api.git</li> 
<li> cd comic-api</li>
</ul>

## 2. Setup ".env" file
<ul>
<li> cp .env.example .env</li> 
</ul>

## 3. Build Docker Images
<ul>
<li>  docker-compose build</li> 
</ul>

## 4. Install composer packages
<ul>
<li> docker-compose run --rm --no-deps php-fpm composer install</li> 
</ul>

## 5. Run Docker
<ul>
<li>  docker-compose up -d</li> 
</ul>

****Api to get 20 recent comics :****
<ul>
<li> http://localhost:8080/api/comic </li> 
</ul>
