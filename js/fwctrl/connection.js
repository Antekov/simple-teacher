$(function(){

	$.get('/services/fwctrl/connection/', function (data) {
		var initialData = data.items

		var PagedGridModel = function(items) {
			this.items = ko.observableArray(items);

			this.addItem = function() {
				this.items.push({ name: "New item", sales: 0, price: 100 });
			};

			this.sortByName = function() {
				this.items.sort(function(a, b) {
					return a.name < b.name ? -1 : 1;
				});
			};

			this.jumpToFirstPage = function() {
				this.gridViewModel.currentPageIndex(0);
			};

			this.gridViewModel = new ko.simpleGrid.viewModel({
				data: this.items,
				columns: [
					{ headerText: "ID", rowText: "id" },
					{ headerText: "Название", rowText: "name" },
					{ headerText: "IP:Порт", rowText: function (item) { return item.ip + ':' + item.port } }
				],
				pageSize: 4
			});
		};

		ko.applyBindings(new PagedGridModel(initialData));
	});

});
