<template>
	<div v-html="patientCallHistory"></div>	 
</template>					 
<script>
export default {
	props: {
		patientCallHistory: String,
	},
};
</script>