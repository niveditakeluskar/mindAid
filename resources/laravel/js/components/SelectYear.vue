<template>
	<select class="custom-select">
		<option v-for="n in 100" :value="current_year-n+2">{{ current_year-n+2 }}</option>
	</select>
</template>

<script>
	export default {
		data () {
			return {
				current_year: (new Date()).getFullYear()
			}
		},
		mounted () {
			$(this.$el).val(this.current_year);
		}
	}
</script>
