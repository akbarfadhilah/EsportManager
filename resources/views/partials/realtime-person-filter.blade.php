<script>
(function () {
    function bindRealtimePersonFilter() {
        var forms = document.querySelectorAll('form');
        var personNameSelectors =
            'select[name="pemilik_id"], select[name="ceo_id"],' +
            'select[name="pelatih_utama_id"], select[name="pelatih_kedua_id"],' +
            'select[name="manager_id"], select[name="analis_id"],' +
            'select[name="ketua_persatuan_game_id"], select[name="pelatih_timnas_id"],' +
            'select[name="players[]"]';

        Array.prototype.forEach.call(forms, function (form) {
            var selects = Array.prototype.slice.call(
                form.querySelectorAll(personNameSelectors)
            );
            if (!selects.length) return;

            function refresh() {
                var taken = new Set();
                selects.forEach(function (s) { if (s.value) taken.add(s.value); });

                selects.forEach(function (s) {
                    Array.prototype.forEach.call(s.options, function (opt) {
                        if (!opt.value) return;
                        var isSelfSelected = s.value === opt.value;
                        if (taken.has(opt.value) && !isSelfSelected) {
                            opt.disabled = true;
                            opt.setAttribute('hidden', 'hidden');
                        } else {
                            opt.disabled = false;
                            opt.removeAttribute('hidden');
                        }
                    });
                });
            }

            selects.forEach(function (s) {
                s.addEventListener('change', refresh);
            });

            refresh();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bindRealtimePersonFilter);
    } else {
        bindRealtimePersonFilter();
    }
})();
</script>