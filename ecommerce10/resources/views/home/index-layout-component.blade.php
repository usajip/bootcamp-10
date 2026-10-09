<x-layout-component title="Home Page" description="Welcome to the Home Page" keywords="home, e-commerce" author="My E-commerce Site">
    <h1>Welcome to the Home Page</h1>
    <p>This is the content of the home page.</p>
    <x-alert-default type="info">
        This is an informational alert.
    </x-alert-default>
    <h1 style="color: red;">This is a styled heading</h1>
    <img src="{{ asset('images/images.jpeg') }}" alt="Example Image">
<style>
    h1 {
        font-family: Arial, sans-serif;
    }
</style>
<script>
    console.log('This is a script block.');
</script>
</x-layout-component>