document.write('<script src="src/controllers/controllerLogin.js?version=1.0"></script>');
document.write('<script src="src/controllers/controllerInicio.js?version=1.0"></script>');

var app = angular.module("appModulo", ['ui.router']);

app.config(function($stateProvider, $urlRouterProvider){
		
		$stateProvider
			
			.state('login',{
				url: '/login',
				templateUrl: 'src/views/login.html',
				controller: 'controllerLogin'
			})
		
		
			.state('inicio',{
				url: '/inicio',
				templateUrl: 'src/views/inicio.html',
				controller: 'controllerInicio'
			})
		
		
		$urlRouterProvider.otherwise('login');
})