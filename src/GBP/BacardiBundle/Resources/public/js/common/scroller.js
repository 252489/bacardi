function Scroller (a) {
	var b = this;
	this.scroller = baron(a);
	this.update = function (a) {
		setTimeout(function () {
				b.scroller.update()
			},
			a | 0)
	};
	this.updateSize = function (a, b, e, f) {
		f = f || 0;
		$(this.scroller[0].bar).show();
		b = b[0]["scroll" + (e.charAt(0).toUpperCase() + e.slice(1))];
		a = a[e]();
		0 >= b - a - f ? $(this.scroller[0].bar).hide() : ($(this.scroller[0].bar).show(), this.update())
	}
}