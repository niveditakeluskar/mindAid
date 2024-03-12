Vue.component("contact-time",  require("./components/ContactTime.vue").default);
Vue.component("select-month",  require("./components/SelectMonth.vue").default);
Vue.component("select-year",   require("./components/SelectYear.vue").default);
Vue.component("search-select", require("./components/SearchSelect.vue").default);
Vue.component("yes-no",        require("./components/YesNo.vue").default);

var app = new Vue({
	el: "#app",
	data () {
		return {
			uid: 1
		}
	},
	methods: {
		/**
		 * Generate the feedback element for error reporting
		 *
		 * @param {Vue Component} component
		 */
		feedback (component) {
			var elem = $(component.$el);
			if (!elem.attr("data-feedback")) {
				var uid = this.nextUid();
				elem.attr("data-feedback", uid);
				elem.after(`<div class="invalid-feedback" data-feedback-area="_fb_${uid}">Test</div>`);
			}
		},

		/**
		 * Get the next uniqued identifier
		 *
		 * @return {Integer}
		 */
		nextUid () {
			return this.uid++;
		}
	}
});
