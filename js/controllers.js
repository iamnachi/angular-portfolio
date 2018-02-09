/*CONFIG*/
app.run(function ($rootScope, $location, $route, $timeout) {
	//$location.path('/');//Add this
	
	$rootScope.layout = {};

	$rootScope.show_loading = function(status) {
        $rootScope.animate = status;
    };

    $rootScope.$on('$routeChangeStart', function () {
        $rootScope.show_loading(true);
    });
	
    $rootScope.$on('$routeChangeSuccess', function () {
        //hide loading gif
		$timeout( function () {	
			$rootScope.show_loading(false);
		}, 500);	
    });
	
    $rootScope.$on('$routeChangeError', function () {
		$rootScope.show_loading(false);
    });
});



app.controller('aboutCtrl', function($scope, aboutData, $rootScope) {
		$scope.hasConfig = false;

		aboutData.getData().then(function(response){
			if(response.status == 200 && response.statusText == "OK") {
				//console.log(response.result);
				$scope.about = response.data.result;
				console.log("about loaded");
			}
		});
		
}); 

app.controller('expCtrl', function($scope, expData, sampleService, sampleFactory, sampleProvider, $rootScope, $location) {

		if($scope.hasConfig) {
			$location.path('/config');
		} else {
			//Stay on page
			console.log('test');
		}

		expData.getData().then(function(response){
			if(response.status == 200 && response.statusText == "OK") {
				//console.log(response);
				$scope.experience = response.data.result;
				console.log("experience loaded");
			}
		});
		
		/* Difference between factory and service */
		//console.log(sampleService); // services wont send primitives
		//console.log(sampleFactory); // factories can recieve primitives
		//console.log(sampleProvider); // function return through factory	
});


app.controller('libraryCtrl', function($scope, bookData, $rootScope) {
		
		$scope.pageSize = 5;
		//$scope.number = ($scope.$index + 1) + ($scope.currentPage - 1) * $scope.pageSize;
		
		bookData.getData().then(function(response){
			//console.log(response);
			if(response.status == 200 && response.statusText == "OK") {
				$scope.books = response.data;
				console.log("library loaded");
			}	
		});
    	
}); 

/* Custom Filters */
app.filter('bytopic', function() {
  return function(books, topics) {
    //console.log("Value is "+ JSON.stringify(topics));
	if(topics == undefined) {
		return books;
	} else {
		var items = [];
		angular.forEach(books, function (value, key) {
			let fill = value.topic
			if(topics[fill] === true) {
				//console.log(key + value.topic);
				items.push(books[key]);	
			}
			
		});
		return items;
	}
  }
});

app.controller('cartCtrl', function($scope, bookData, cart, $rootScope, $location) {
		
		/*
		bookData.getData().then(function(response){
			//console.log(response);
			if(response.status == 200 && response.statusText == "OK") {
				$scope.books = response.data;
				console.log("cart loaded");
			}	
		});*/
		cart.load_books().then(function(response){
			$scope.books = 	response;
		});

		$scope.checked = true;

		$scope.cartCount = cart.get_cart_count();

		$scope.add_to_cart = function (product_id) {
			if(cart.add_to_cart(product_id)) {
				var count = $scope.cartCount;
				$scope.cartCount = count + 1;
				console.log("added to cart");
			}
		}

		$scope.checkout = function () {
			$location.path('/Checkout')
		}

		/*
		$scope.get_product_info = function (product_id) {
			let books = $scope.books;
			var trigger = 0;
			angular.forEach($scope.books, function(value, key) {
				if(product_id == value.bookId) {
					trigger = value;
				}
			});

			if(trigger != 0) {
				return trigger;
			} else {
				return false;
			}
		}

		$scope.get_cart = function () {
			var LOCAL_STORAGE_ID = 'cart_products';
			var item = sessionStorage[LOCAL_STORAGE_ID];
			return item || false;
		}

		$scope.get_cart_count = function () {
			var product_data = $scope.get_cart();
			var count = 0;
			if(product_data) {
				angular.forEach(JSON.parse(product_data), function(v, k) {
					count = count +1;
				});
			}
			return count;
		}

		$scope.is_product_in_cart = function (product_id) {
			if(!product_id) return false;
			var product_data = $scope.get_cart();
			var response = false;
			if(product_data) {
				angular.forEach(JSON.parse(product_data), function(v, k) {
					if(v.bookId == product_id) {
						response = true;
					}
				});
			}
			return response;
		}

		$scope.add_to_cart = function (product_id) {
			console.log(product_id);
			console.log($scope.get_product_info(product_id));
			if(!$scope.get_product_info(product_id)) {
				alert("no such product");
				return false;
			}
			if($scope.is_product_in_cart(product_id)) {
				alert("Book is already added to cart!");
				return false;
			}
			var products = [];
			var product_data = $scope.get_cart();

			if(product_data) {
				angular.forEach(JSON.parse(product_data), function(v, k) {
					products.push(v); 
				});
			}
			products.push($scope.get_product_info(product_id));
			sessionStorage.setItem("cart_products", JSON.stringify(products));
			var count = $scope.cartCount;
			$scope.cartCount = count + 1;
			console.log("added to cart");
		}
		*/
		
}); 


app.controller('checkoutCtrl', function($scope, $rootScope, cart, $uibModal, $log, $document) {
		
		$scope.carts = JSON.parse(cart.get_cart());
		//console.log($scope.carts);
		let carts = $scope.carts;
		var $ctrl = this;
		
		//console.log($scope.carts);
		$scope.qty = {};

		$scope.tax = function (prodTotal) {
			$scope.productTotal = prodTotal;
			$scope.SGST = prodTotal * (5 / 100);
			$scope.CGST = prodTotal * (5 / 100);
			$scope.totalAmount = parseFloat($scope.productTotal) + parseFloat($scope.SGST) + parseFloat($scope.CGST);
			$scope.shipping = $scope.totalAmount < 500 ? 50.00 : 'Free';
			$scope.totalAmount = $scope.shipping != 'Free' ? parseFloat($scope.shipping) + parseFloat($scope.totalAmount) : parseFloat($scope.totalAmount)
		}

		$scope.init = function () {
			var prodTotal = 0;
			angular.forEach(carts, function(v, k) {
				let bookId = v.bookId;
				let qty = $scope.qty;
				if(qty[bookId] == undefined) {
					qty[bookId] = 1;
				}
				let cost = qty[bookId] * v.cost;
				//console.log(cost);
				prodTotal = parseFloat(cost) + parseFloat(prodTotal); 
				//console.log(prodTotal);
			});
			//console.log(prodTotal);
			$scope.tax(prodTotal);
		}

		$ctrl.animationsEnabled = true;

		$ctrl.openComponentModal = function () {
			var modalInstance = $uibModal.open({
			animation: $ctrl.animationsEnabled,
			component: 'modalComponent',
			resolve: {

			}
			});
			
			modalInstance.result.then(function () {
				//$ctrl.selected = selectedItem;
			}, function () {
				$log.info('modal-component dismissed at: ' + new Date());
			});
		};

		
		//console.log('Checkout loaded');
});

angular.module('ui.bootstrap').controller('ModalInstanceCtrl', function ($uibModalInstance, items) {
  var $ctrl = this;
  /*
  $ctrl.items = items;
  $ctrl.selected = {
    item: $ctrl.items[0]
  };*/

  $ctrl.ok = function () {
    $uibModalInstance.close("test");
  };

  $ctrl.cancel = function () {
    $uibModalInstance.dismiss('cancel');
  };
});


angular.module('ui.bootstrap').component('modalComponent', {
  templateUrl: 'myModalContent.html',
  bindings: {
    resolve: '<',
    close: '&',
    dismiss: '&'
  },
  controller: function () {
    var $ctrl = this;

    $ctrl.$onInit = function () {
      /*
	  $ctrl.items = $ctrl.resolve.items;
      $ctrl.selected = {
        item: $ctrl.items[0]
      };*/
    };

    $ctrl.ok = function () {
      /*$ctrl.close({$value: $ctrl.selected.item});*/
    };

    $ctrl.cancel = function () {
      /*$ctrl.dismiss({$value: 'cancel'});*/
    };
  }
});






