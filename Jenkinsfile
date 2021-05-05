pipeline {
  agent any
  stages {
    stage('build') {
      steps {
        git(url: 'https://github.com/jtonello/chef-resources', branch: 'main')
      }
    }

  }
}