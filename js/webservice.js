function todolist() {	
	let list = [];	
	return {
			addTodo: function(todo) {
				list.push(todo);
			},
			removeTodo: function(todo) {
				for (var i = 0; i < list.length; i++) {
					if (list[i] === todo) { 
						list.splice(i, 1);
						break;
					}
				}	
			},
			showTodo: function() {
				return list.toString();
			}
	}
}


app.service('sampleService', function() { // services wont send primitives
	return "test";	
	//return todolist();	
	// use this.value this.function
});

app.factory('sampleFactory', function() { // factories can recieve primitives
	return "hello";
	//return todolist();
	// use obj = {}, obj.value, obj.function 
});


app.service('aboutData', function($http) {
	this.getData = function () {
		return $http.get("data/about.json");
		/*
		var promise = $http.get("data/about.json").then(function (response) {
						return response.data;
					}).catch(function (httpError) {
						return httpError.status + " : " +  httpError.data;
					});
		return promise;
		*/
	}
});

app.factory('expData', function($http) {
	let val = {};
	
	val.getData =  function () {
		return $http.get("data/experience.json"); // object
		
	}
	return val;
});


app.factory('bookData', function($http) {
	return {
		getData: function() {
			return $http.get("data/books.json"); 
		}
	}
});


app.factory('cart', function($http, bookData, $q) {
	let method = {};
	let books;
	
	method.load_books = function () {
		var deffered = $q.defer();
		bookData.getData().then(function(response){
			if(response.status == 200 && response.statusText == "OK") {
				console.log("cart loaded");
				books = response.data;
				deffered.resolve(response.data);	
			}	
		}).catch(function(error) {
			console.log("error "+ error);
			books = error;
			deffered.reject(error);
		});

		books = deffered.promise;
		return $q.when(books);
			
	}

	method.get_cart = function () {
		let LOCAL_STORAGE_ID = 'cart_products';
		let item = sessionStorage[LOCAL_STORAGE_ID];
		return item || false;
	}

	method.get_cart_count = function () {
		var product_data = method.get_cart();
		//console.log(product_data);
		var count;
		if(product_data != false && product_data != null) {
			count = JSON.parse(product_data).length; 
		} else {
			count = 0;
		}
		
		return count;
	}

	method.is_product_in_cart = function (product_id) {
		if(!product_id) return false;
		var product_data = method.get_cart();
		var response = false;
		if(product_data != false && product_data != null) {
			angular.forEach(JSON.parse(product_data), function(v, k) {
				if(v.bookId == product_id) {
					response = true;
				}
			});
		}
		return response;
	}

	method.get_product_info = function (product_id) {
		var trigger = 0;
		/*
		var loop =[];
		console.log("books" + JSON.stringify(books));
		method.load_books().then(function(books) {
			console.log("books" + JSON.stringify(books));
			loop.push(books);
		});
		console.log(loop);*/
		angular.forEach(books, function(value, key) {
			if(product_id == value.bookId) {
				trigger = value;
			}
		});
		//console.log(trigger);
		return trigger;
	}

	method.add_to_cart = function (product_id) {
		//console.log(product_id);
		//console.log($scope.get_product_info(product_id));
		if(!method.get_product_info(product_id)) {
			alert("no such product");
			return false;
		}
		if(method.is_product_in_cart(product_id)) {
			alert("Book is already added to cart!");
			return false;
		}
		var products = [], i=0;
		var product_data = method.get_cart();

		if(product_data) {
			angular.forEach(JSON.parse(product_data), function(v, k) {
				products[i] = v; 
			});
			i++;
		}
		products[i] = angular.copy(method.get_product_info(product_id));
		console.log(products);
		sessionStorage.setItem("cart_products", JSON.stringify(products));
		return true;
	}

	method.get_cart = function () {
		let products = sessionStorage.getItem("cart_products");
		return products;
	}

	return method;
})