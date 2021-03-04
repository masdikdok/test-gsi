window.Alert = {
    toast : function (type, message, is_html = false, timer = 1750){
        var _array = ['success', 'error', 'info', 'question', 'warning', 'danger'],
            _type = _array.find(function(item){
                return item == type.replace(' ', '');
            }),
            type = typeof(_type) == 'undefined' ? 'info' : _type,
            params = {
                position: 'bottom-end',
                icon: type,
                title: type[0].toUpperCase() + type.substr(1),
                showCloseButton: true,
                showConfirmButton : false,
                toast: true,
                timer: timer
            };

        if (is_html) {
            Object.assign(params, {
                html: (message) ? message : '<p>This is message</p>',
            });
        }else{
            Object.assign(params, {
                text: (message) ? message : 'This is message',
            });
        }

        Swal.fire(params);
    },
};

window.Confirm = {
    delete : function (callback = false, ){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showClass: {
                popup: 'animated fadeInDown faster'
            },
            hideClass: {
                popup: 'animated fadeOutUp faster'
            },
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                if (callback) {
                    callback(result);
                }
            }
        });

    },
    approve : function (callback = false){

        Swal.fire({
            title: 'Are you sure?',
            html: 'The data cannot be edit or delete after you approved! <br>Please fill the data correctly before you approve it!',
            icon: 'info',
            showClass: {
                popup: 'animated fadeInDown faster'
            },
            hideClass: {
                popup: 'animated fadeOutUp faster'
            },
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Continue, approve it!'
        }).then((result) => {
            if (result.value) {
                callback(result)
            }

            return result.value;
        })

    },
    process : function (callback = false){

        Swal.fire({
            title: 'Are you sure?',
            html: 'The data cannot be edit or delete after you process it! <br><p class="small">Please fill the data correctly before you process it!</p>',
            icon: 'info',
            showClass: {
                popup: 'animated fadeInDown faster'
            },
            hideClass: {
                popup: 'animated fadeOutUp faster'
            },
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Continue, process it!'
        }).then((result) => {
            if (result.value) {
                callback(result)
            }

            return result.value;
        })

    }
};

window.Ajax = {
    call : function (params, url, callback = false, method = 'POST', onBackground = false){
        $.ajax({
            url: url,
            method: method,
            dataType: 'json',
            data: params,
            beforeSend: function(){
                if (onBackground == false) {
                    $('body').addClass('_loading_');
                }
            },
            success: function(resp){
                if(callback !== false){
                    callback(resp);
                }
            },
            complete: function(){
                $('body').removeClass('_loading_');
            },
            error: function(xhr, status, error){
                Ajax.showError(xhr);
            }
        });
    },
    callFormData : function (params, url, callback = false, method = 'POST', onBackground = false){

        $.ajax({
            url: url,
            method: method,
            dataType: 'json',
            data: params,
            contentType: false,
            processData: false,
            beforeSend: function(){
                if (onBackground == false) {
                    $('body').addClass('_loading_');
                }
            },
            success: function(resp){
                if(callback !== false){
                    callback(resp);
                }
            },
            complete: function(){
                $('body').removeClass('_loading_');
            },
            error: function(xhr, status, error){
                Ajax.showError(xhr);
            }
        });
    },
    showError: function(xhr){
        var resp = xhr.responseJSON,
            tempHtml = ``;

        tempHtml += `
            <p class="mb-2">`+ resp.message +`</p>
            <ol class="pl-2">
        `;

        for(index in resp.errors){
            var data = resp.errors[index],
                htmlInfo = ``;

            if (data.length > 0) {
                htmlInfo += `
                    <ul class="mb-1 mt-0 pl-3 small">
                `;

                data.forEach(function(message, f){
                    htmlInfo += `
                        <li>`+ message +`</li>
                    `;
                });

                htmlInfo += `</ul>`;
            }

            tempHtml += `
                <li class="pl-2">
                    <div class="d-flex justify-content-between">
                        <div class="cart-product">
                            <p class="mb-0">`+ index +`</p>
                            `+ htmlInfo +`
                        </div>
                    </div>
                </li>
            `;
        }

        tempHtml += '</ol>';
        //
        Alert.toast('error', tempHtml, true, 4000);
    }

};

window.customViewClient = {
    baseUrl : $('meta[name=url]').attr('content'),
    mainTable : false,
    renderSelect2 : function(target, timeout = 150){
        setTimeout(function(){
            $(target).each(function(index, element){
                var _ = $(element);

                if(_.hasClass('_renderAjax_') && !! _.attr('data-url') && ! _.attr('readonly') && ! _.attr('disabled')){
                    _.select2({
                        placeholder:"Please select one",
                        ajax: {
                            url: _.attr('data-url'),
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                let data = {
                                    q: params.term, // search term
                                    page: params.page,
                                };

                                if (!! _.data('reference')) {
                                    Object.assign(data, {reference : $(_.data('reference')).val()});
                                }

                                if (!! _.data('status')) {
                                    Object.assign(data, {status : _.data('status')});
                                }

                                return data;
                            },
                            processResults: function(data, params) {
                                // parse the results into the format expected by Select2
                                // since we are using custom formatting functions we do not need to
                                // alter the remote JSON data, except to indicate that infinite
                                // scrolling can be used
                                params.page = params.page || 1;

                                return {
                                    results: data.items,
                                    pagination: {
                                        more: (params.page * 10) < data.totalData && data.items.length > 10
                                    }
                                };
                            },
                            cache: true
                        },
                        templateResult: function(item){
                            if(item.loading){
                                return item.text;
                            }

                            return item.name;
                        },
                        templateSelection: function(item){
                            if(item.text){
                                return item.text;
                            }

                            return item.name;
                        },
                    });
                }else{
                    _.select2({
                        dropdownParent : _.parent(),
                        readonly : (!! _.attr('readonly')) ? true : false,
                        disabled : (!! _.attr('disabled') || !! _.attr('readonly')) ? true : false,
                    });
                }
            });
        }, timeout);
    }
};

// Global function and action
(function($){
    'use strict';
    $(function(){
        // FIRST SETUP LAYOUT
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // INITIAL RENDER
        customViewClient.renderSelect2('._select2_');

        if ($('input[name^=alert]').length > 0) {
            var type = $('input[name="alert[type]"]').val(),
                message = $('input[name="alert[message]"]').val(),
                is_html = $('input[name="alert[is_html]"]').val(),
                timer = $('input[name="alert[timer]"]').val();

            Alert.toast(type, message, is_html, timer);
        }

        $('.table-responsive').on('show.bs.dropdown', function () {
             $('.table-responsive').css( "overflow", "inherit" );
        });

        $('.table-responsive').on('hide.bs.dropdown', function () {
             $('.table-responsive').css( "overflow", "auto" );
        })

        $(document).on('click', '._delete_row_', function(){
            var _ = $(this),
                row = _.closest('tr'),
                id = _.attr('data-id'),
                url = _.attr('data-url');

            if(id == '' || id == null || typeof(id) == 'undefined' || url == '' || url == null || typeof(url) == 'undefined'){
                Alert.toast('error', 'Failed because data is not correct');
                return false;
            }

            Confirm.delete((resp) => {
                $.ajax({
                    url: url,
                    method: 'post',
                    dataType: 'json',
                    data: {
                        id : id
                    },
                    beforeSend: function(){
                        _.attr('disabled', 'disabled');
                    },
                    success: function(resp){

                        if(resp.result == 1 || resp.result == '1'){
                            if (customViewClient.mainTable) {
                                customViewClient.mainTable.rows(row).remove().draw();
                            }else{
                                row.remove();
                            }
                        }

                        Alert.toast(resp.alert.type, resp.alert.message);
                    },
                    complete: function(){
                        _.removeAttr('disabled');
                    }
                });
            });

        });
    });
})(jQuery);
