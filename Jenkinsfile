pipeline {
  agent {
    docker {
      image 'php'
    }
    
  }
  stages {
    stage('Build') {
      steps {
        node(label: 'jenkins')
      }
    }
  }
}