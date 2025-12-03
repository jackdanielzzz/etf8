$(() => {
	$(() => {
		let obj = $('.sideBar');
		let content = $('.sideBar-content');
		let page = $('body');

		function toggle() {
			obj.toggleClass('sideBar_visible');
			page.toggleClass('noScroll');

		}

		$('.js-sideBarCtrl').click(toggle);
		content.click((e) => {
			e.stopPropagation();
		});
		obj.click(toggle);
	});

	let Menu = {

		el: {
			ham: $('.menu_burger'),
			menuTop: $('.menu-top'),
			menuMiddle: $('.menu-middle'),
			menuBottom: $('.menu-bottom')
		},

		init: function() {
			Menu.bindUIactions();
		},

		bindUIactions: function() {
			Menu.el.ham
				.on(
					'click',
					function(event) {
						Menu.activateMenu(event);
						event.preventDefault();
					}
				);
		},

		activateMenu: function() {
			Menu.el.menuTop.toggleClass('menu-top-click');
			Menu.el.menuMiddle.toggleClass('menu-middle-click');
			Menu.el.menuBottom.toggleClass('menu-bottom-click');
		}
	};

	Menu.init();
});

$(() => {
	let obj = $('.sideBar2');
	let content = $('.sideBar-content2');
	let page = $('body');

	function toggle() {
		obj.toggleClass('sideBar_visible');
		page.toggleClass('noScroll');

	}

	$('.js-sideBarCtrl2').click(toggle);
	content.click((e) => {
		// e.stopPropagation();
	});
	obj.click(toggle);
});