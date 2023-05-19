<template>
	<select class="selectpicker form-control show-tick" data-live-search="1" data-style="custom-select">
		<option value="" v-if="noneOption !== undefined">None</option>
		<slot></slot>
	</select>
</template>

<script>
	export default {
		mounted () {
			this.$root.feedback(this);
		},
		props: ["noneOption"]
	}
</script>
