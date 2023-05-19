<template>
	<select class="custom-select">
		<!-- There is no error here -->
		<option v-for="(month, index) in months" :value="index">{{ month }}</option>
	</select>
</template>

<script>
	export default {
		data () {
			return {
				current_month: (new Date()).getMonth(),
				months: [
					"January",
					"February",
					"March",
					"April",
					"May",
					"June",
					"July",
					"August",
					"September",
					"October",
					"November",
					"December"
				]
			}
		},
		mounted () {
			$(this.$el).val(this.current_month);
		}
	}
</script>
