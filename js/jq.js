$(function () {
    leave = 0;
    flag = 0;
    prices = 0;
    $("input:text").bind({
        focus: function () {
            if (this.value == this.defaultValue) {
                this.value = "";
            }
        },
        blur: function () {
            if (this.value == "") {
                this.value = this.defaultValue;
            }
        }
    });
    start();
    /*$('#zimu a').click(function () {
     mScroll($(this).text());
     })*/
    $('.M_classify ul li').eq(0).find("p a").click(function () {
        leave = $(this).index() ;
        searchcon(leave, flag, prices);
    })
    $('.M_classify ul li').eq(1).find("p a").click(function () {
        flag = $(this).index() ;
        searchcon(leave, flag, prices);
    })
    $('.M_classify ul li').eq(2).find("p a").click(function () {
        var reg = /[\u4E00-\u9FA5]/g;
        prices = $(this).text().replace(reg, "");
        (prices) ? prices = prices : prices = 0;
        searchcon(leave, flag, prices);

    })
    $("#zimu").on("click", "a", function () {
        mScroll($(this).text());
    });

    $('.M_search input').eq(1).click(function () {
        search($(".M_search input").eq(0).val());
    })

});
Array.prototype.unique1 = function () {
    var n = []; //一个新的临时数组
    for (var key in this) //遍历当前数组
    {
        //如果当前数组的第i已经保存进了临时数组，那么跳过，
        //否则把当前项push到临时数组里面
        if (n.indexOf(this[key].englishName) == -1) n.push(data[key].englishName);
    }
    return n;
}

function unique1(data) {
    var n = []; //一个新的临时数组
    for (var key in data) //遍历当前数组
    {
        //如果当前数组的第i已经保存进了临时数组，那么跳过，
        //否则把当前项push到临时数组里面
        if (n.indexOf(data[key].englishName) == -1)n.push(data[key].englishName);
    }

    var b = [];//去除undefined后的结果
    for (var i = 0; i < n.length; i++) {
        if (typeof(n[i]) != 'undefined') {
            b.push(n[i]);
        }
    }
    return b;
}

Array.prototype.unique2 = function () {
    var n = []; //一个新的临时数组
    for (var i = 0; i < this.length; i++) //遍历当前数组
    {
        //如果当前数组的第i已经保存进了临时数组，那么跳过，
        //否则把当前项push到临时数组里面
        if (n.indexOf(this[i].id) == -1) n.push(this[i].englishName);
    }

    return n;
}
function mScroll(id) {
    $("html,body").stop(true);
    $("html,body").animate({scrollTop: $("#" + id).offset().top}, 10);
}

function search(text) {

    var nt = new Date().getTime();
    $.ajax({
        type: "GET",
        url: "/allcardata.json?t=" + nt,
        async: false,
        dataType: "json",
        success: function (data) {

            var sdata = [];
            $.each(data, function (n, value) {
                if (value.name.indexOf(text) >= 0 && value.type == "SERIES") {
                    console.log(value.name);
                    var sid = value.id;
                    sdata[sid] = data[sid];
                    var orid = value.parentId;
                    var brid = data[orid]['parentId'];
                    sdata[orid] = data[orid];
                    sdata[brid] = data[brid];
                    // console.log(value.name);
                }

            })
            var b = [];//去除undefined后的结果
            for (var i = 0; i < sdata.length; i++) {
                if (typeof(sdata[i]) != 'undefined') {
                    b.push(sdata[i]);
                }
            }

            console.log(b);

            var dt = unique1(b);
            // dt.pop();
            console.log(dt);
            $(".M_audi").html("");
            $("#zimu").html("");

            $.each(dt, function (n, value) {
                $(".M_audi").append('<dl><dt id="' + value + '">' + value + '</dt></dl>');
                $("#zimu").append('<a href="javascript:void(0);">' + value + '</a>');
            })
            $.each(b, function (n, value) {

                if (value.type == "BRAND") {
                    $('#' + value.englishName).parent().append('<dd class="clearfix" id="' + value.englishName + value.id + '"><div class="M_l fl"><img height="82" width="82" alt="" src="images/logo_1.jpg"><p>' + value.name + '</p></div><div class="brand fr"> </div></dd>');
                }

            });
            $.each(b, function (n, value) {


                if (value.type == "ORGANIZA") {
                    $('#' + value.englishName + value.parentId).find(".brand").append('<h3>' + value.name + '</h3> <ul class="clearfix" id="' + value.englishName + value.id + '"> </ul>');
                }

            });
            $.each(b, function (n, value) {


                if (value.type == "SERIES") {
                    (value.price == "暂无") ? price = "暂无" : price = value.price + "-" + value.priceb + "万";
                    $('#' + value.englishName + value.parentId).append('<li> <em><a href="#"><img src="images/ph_2.jpg"></a></em> <strong><a href="#">' + value.name + '</a></strong> <p>厂商指导价：' + price + '</p> <span><a href="#">参数</a><a href="#">图库</a></span> </li>');
                }
            });
        }
    });
}

function start() {
    var nt = new Date().getTime();
    $.ajax({
        type: "GET",
        url: "/allcardata.json?t=" + nt,
        async: false,
        dataType: "json",
        success: function (data) {
            var trs = "";

            var dt = unique1(data);
            $.each(dt, function (n, value) {
                $(".M_audi").append('<dl><dt id="' + value + '">' + value + '</dt></dl>');
                $("#zimu").append('<a href="javascript:void(0);">' + value + '</a>');
            })
            $.each(data, function (n, value) {
                if (value.type == "BRAND") {
                    $('#' + value.englishName).parent().append('<dd class="clearfix" id="' + value.englishName + value.id + '"><div class="M_l fl"><img height="82" width="82" alt="" src="images/logo_1.jpg"><p>' + value.name + '</p></div><div class="brand fr"> </div></dd>');
                }

            });
            $.each(data, function (n, value) {

                if (value.type == "ORGANIZA") {
                    $('#' + value.englishName + value.parentId).find(".brand").append('<h3>' + value.name + '</h3> <ul class="clearfix" id="' + value.englishName + value.id + '"> </ul>');
                }

            });
            $.each(data, function (n, value) {

                if (value.type == "SERIES") {
                    (value.price == "暂无") ? price = "暂无" : price = value.price + "-" + value.priceb + "万";
                    $('#' + value.englishName + value.parentId).append('<li> <em><a href="#"><img src="images/ph_2.jpg"></a></em> <strong><a href="#">' + value.name + '</a></strong> <p>厂商指导价：' + price + '</p> <span><a href="#">参数</a><a href="#">图库</a></span> </li>');
                }
            });
        }
    });
}

function searchcon(leave, flag, prices) {

    var nt = new Date().getTime();
    $.ajax({
        type: "GET",
        url: "/allcardata.json?t=" + nt,
        async: false,
        dataType: "json",
        success: function (data) {
            var sdata = [];
            var sdata1 = [];
            var sdata2 = [];
            if (leave != 0) {
                $.each(data, function (n, value) {
                    if (value.leave == leave && value.type == "SERIES") {
                        var sid = value.id;
                        sdata[sid] = data[sid];
                        var orid = value.parentId;
                        var brid = data[orid]['parentId'];
                        sdata[orid] = data[orid];
                        sdata[brid] = data[brid];
                       // console.log(sdata);
                    }

                })
            }


            if (flag != 0) {
                (sdata!="") ? sdata = sdata : sdata = data;

                var b = [];//去除undefined后的结果
                for (var i = 0; i < sdata.length; i++) {
                    if (typeof(sdata[i]) != 'undefined') {
                        b.push(sdata[i]);
                    }
                }
                sdata=[];
                sdata=b;
                $.each(sdata, function (n, value) {

                    if (value.flag == flag && value.type == "SERIES") {
                        var sid = value.id;
                        sdata1[sid] = data[sid];
                        var orid = value.parentId;
                        var brid = data[orid]['parentId'];
                        sdata1[orid] = data[orid];
                        sdata1[brid] = data[brid];
                    }
                })
                sdata=[];
                sdata=sdata1;
            }
            if (prices != 0) {
                (sdata!="") ? sdata = sdata : sdata = data;
                var b = [];//去除undefined后的结果
                for (var i = 0; i < sdata.length; i++) {
                    if (typeof(sdata[i]) != 'undefined') {
                        b.push(sdata[i]);
                    }
                }
                sdata=[];
                sdata=b;
                $.each(sdata, function (n, value) {
                    price1 = parseInt(prices.split("-")[0]);
                    (prices.split("-")[1]) ? priceb = parseInt(prices.split("-")[1]) : priceb = 0;
                    console.log(priceb);
                    if (parseInt(value.price) != 5   && value.type == "SERIES" &&( (parseInt(value.price)>=price1 && parseInt(value.price)<=priceb ) || (parseInt(value.priceb)>=price1 && parseInt(value.priceb)<=priceb))) {
                        var sid = value.id;
                        sdata2[sid] = data[sid];
                        var orid = value.parentId;
                        var brid = data[orid]['parentId'];
                        sdata2[orid] = data[orid];
                        sdata2[brid] = data[brid];
                         console.log(sdata);
                    }
                    if (value.price == 5 && value.price>=price1 &&value.priceb<=priceb && value.type == "SERIES") {
                        var sid = value.id;
                        sdata[sid] = data[sid];
                        var orid = value.parentId;
                        var brid = data[orid]['parentId'];
                        sdata[orid] = data[orid];
                        sdata[brid] = data[brid];
                        // console.log(value.name);
                    }

                })
                sdata=[];
                sdata=sdata2;
            }

            var b = [];//去除undefined后的结果
            for (var i = 0; i < sdata.length; i++) {
                if (typeof(sdata[i]) != 'undefined') {
                    b.push(sdata[i]);
                }
            }

            //console.log(b);

            var dt = unique1(b);
            // dt.pop();
           // console.log(dt);
            $(".M_audi").html("");
            $("#zimu").html("");

            $.each(dt, function (n, value) {
                $(".M_audi").append('<dl><dt id="' + value + '">' + value + '</dt></dl>');
                $("#zimu").append('<a href="javascript:void(0);">' + value + '</a>');
            })
            $.each(b, function (n, value) {

                if (value.type == "BRAND") {
                    $('#' + value.englishName).parent().append('<dd class="clearfix" id="' + value.englishName + value.id + '"><div class="M_l fl"><img height="82" width="82" alt="" src="images/logo_1.jpg"><p>' + value.name + '</p></div><div class="brand fr"> </div></dd>');
                }

            });
            $.each(b, function (n, value) {


                if (value.type == "ORGANIZA") {
                    $('#' + value.englishName + value.parentId).find(".brand").append('<h3>' + value.name + '</h3> <ul class="clearfix" id="' + value.englishName + value.id + '"> </ul>');
                }

            });
            $.each(b, function (n, value) {


                if (value.type == "SERIES") {
                    (value.price == "暂无") ? price = "暂无" : price = value.price + "-" + value.priceb + "万";
                    $('#' + value.englishName + value.parentId).append('<li> <em><a href="#"><img src="images/ph_2.jpg"></a></em> <strong><a href="#">' + value.name + '</a></strong> <p>厂商指导价：' + price + '</p> <span><a href="#">参数</a><a href="#">图库</a></span> </li>');
                }
            });
        }
    });
}