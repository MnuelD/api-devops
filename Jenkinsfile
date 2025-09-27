pipeline {
    agent any

    environment {
    MYSQL_ROOT_PASSWORD = 'root'
    MYSQL_DATABASE = 'laravel_db'
    MYSQL_USER = 'laravel_user'
    MYSQL_PASSWORD = 'secret'
    DB_PORT = '3307'
    DB_HOST = '127.0.0.1'
}


    stages {
        stage('Checkout') {
            steps {
                git branch: 'main', url: 'https://github.com/MnuelD/api-devops.git'
            }
        }

        stage('Setup MySQL') {
            steps {
                // Inicia container MySQL local
                bat """
                docker run -d --name laravel-mysql ^
                -e MYSQL_ROOT_PASSWORD=%MYSQL_ROOT_PASSWORD% ^
                -e MYSQL_DATABASE=%MYSQL_DATABASE% ^
                -e MYSQL_USER=%MYSQL_USER% ^
                -e MYSQL_PASSWORD=%MYSQL_PASSWORD% ^
                -p %DB_PORT%:3306 ^
                mysql:8.0
                """
                // Pausa de 30 segundos para o MySQL subir
                powershell 'Start-Sleep -Seconds 30'
            }
        }

        stage('Prepare .env') {
            steps {
                // Copia .env.example e atualiza variáveis
                bat 'copy .env.example .env'
                bat 'powershell -Command "(Get-Content .env) | ForEach-Object {$_ -replace \'DB_CONNECTION=sqlite\', \'DB_CONNECTION=mysql\'} | Set-Content .env"'
                bat "powershell -Command \"(Get-Content .env) | ForEach-Object {$_ -replace 'DB_HOST=127.0.0.1', 'DB_HOST=localhost'} | Set-Content .env\""
                bat "powershell -Command \"(Get-Content .env) | ForEach-Object {$_ -replace 'DB_PORT=3306', 'DB_PORT=%DB_PORT%'} | Set-Content .env\""
                bat "powershell -Command \"(Get-Content .env) | ForEach-Object {$_ -replace 'DB_DATABASE=laravel', 'DB_DATABASE=%MYSQL_DATABASE%'} | Set-Content .env\""
                bat "powershell -Command \"(Get-Content .env) | ForEach-Object {$_ -replace 'DB_USERNAME=root', 'DB_USERNAME=%MYSQL_USER%'} | Set-Content .env\""
                bat "powershell -Command \"(Get-Content .env) | ForEach-Object {$_ -replace 'DB_PASSWORD=', 'DB_PASSWORD=%MYSQL_PASSWORD%'} | Set-Content .env\""
            }
        }

        stage('Install Dependencies') {
            steps {
                bat 'composer install --prefer-dist --no-interaction --no-progress'
                bat 'php artisan config:clear'
                bat 'php artisan cache:clear'
                bat 'php artisan key:generate --force'
                bat 'php artisan migrate:fresh --seed'
            }
        }

        stage('Run Tests') {
            steps {
                bat 'php artisan test'
            }
        }

        stage('Build Docker Image') {
            steps {
                bat 'docker build -t laravel-api-app .'
            }
        }

        stage('Deploy') {
            steps {
                bat 'docker-compose down || exit 0'
                bat 'docker-compose up -d --build'
            }
        }
    }

    post {
        always {
            // Remove container MySQL depois do build
            bat 'docker rm -f laravel-mysql || exit 0'
        }
    }
}
