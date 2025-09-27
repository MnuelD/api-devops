pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                git branch: 'main', url: 'https://github.com/MnuelD/api-devops.git'
            }
        }

        stage('Install dependencies') {
            steps {
                dir('src') {
                    sh 'composer install --prefer-dist --no-interaction --no-progress'
                    sh 'cp .env.example .env || true'
                    sh 'php artisan key:generate'
                }
            }
        }

        stage('Run Tests') {
            steps {
                dir('src') {
                    sh 'php artisan migrate:fresh --seed'
                    sh 'php artisan test'
                }
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
