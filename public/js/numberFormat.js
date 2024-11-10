function number_format(number, decimals, dec_point, thousands_sep) {
    // Format number to string
    number = (number + "").replace(",", "").replace(" ", "");
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = typeof thousands_sep === "undefined" ? "," : thousands_sep,
        dec = typeof dec_point === "undefined" ? "." : dec_point,
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return "" + Math.round(n * k) / k;
            return (
                (prec ? toFixedFix(n, prec) : "" + Math.round(n)).replace(
                    /(\d)(?=(\d{3})+(?!\d))/g,
                    "$1" + sep
                ) +
                (prec
                    ? dec +
                      ("" + Math.abs(n - Math.round(n))).slice(2, prec + 2)
                    : "")
            );
        };
    return toFixedFix(n, prec);
}
