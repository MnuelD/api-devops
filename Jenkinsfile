pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                git branch: 'main', url: 'https://github.com/MnuelD/api-devops.git'
            }
        }

        stage('Install Dependencies') {
    steps {
        sh 'composer install --prefer-dist --no-interaction --no-progress'
        sh 'php artisan config:clear'
        sh 'php artisan cache:clear'
        sh 'php artisan key:generate --force'
        sh 'php artisan migrate:fresh --seed'
    }
}


        stage('Run Tests') {
            steps {
                sh 'php artisan migrate:fresh --seed'
                sh 'php artisan test'
            }
        }

        stage('Build Docker Image') {
            steps {
                sh 'docker build -t laravel-api-app .'
            }
        }

        stage('Deploy') {
            steps {
                sh 'docker-compose down || true'
                sh 'docker-compose up -d --build'
            }
        }
    }
}
