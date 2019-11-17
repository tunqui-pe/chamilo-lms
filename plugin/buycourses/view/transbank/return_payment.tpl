<script>window.localStorage.clear();</script>;
<script>window.localStorage.setItem("authorizationCode","{{ authorizationCode }}");</script>;
<script>window.localStorage.setItem("amount","{{ amount }}");</script>;
<script>window.localStorage.setItem("responseCode", "{{ responseCode }}");</script>;

{{ form }}

<script>
    document.getElementById("return-form").submit();
</script>