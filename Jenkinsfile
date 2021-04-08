pipeline {
  agent any
  stages {
    stage('build') {
      steps {
        git(url: 'https://github.com/jtonello/chef-resources', branch: 'main', credentialsId: 'jtonello')
      }
    }

    stage('deploy') {
      steps {
        build 'docker-build'
      }
    }

  }
}