<template>
<footer class="footer">
    {{ title }} {{ new Date().getFullYear() }}
</footer>
</template>

<script>
export default {
    name: 'Footer',
    props: {
        title: String
    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped>
.footer {
    width: 100%;
    border-top: 1px solid black;
    font-size: 0.8em;
    padding: 10px 0;
    margin: 100px auto 0 auto;
    position: fixed;
    bottom: 0;
    background: white;
}
</style>
