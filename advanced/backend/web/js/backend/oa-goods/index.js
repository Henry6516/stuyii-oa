/*var krajeeYiiConfirm = function(dialog) {
    dialog = dialog || 'krajeeDialog';
    var krajeeDialog = window[dialog] || '';
    if (!krajeeDialog) {
        return;
    }
    var yii.confirm = function (message, ok, cancel) {
        krajeeDialog.confirm(message, function(result) {
            if (result) {
                !ok || ok();
            } else {
                !cancel || cancel();
            }
        });
    };
};*/
//删除按钮
$('.index-delete').on('click',  function () {
    var self = this;
    krajeeDialog.confirm("确定删除此条记录?", function (result) {
        if (result) {
           var id = $(self).closest('tr').data('key');
            $.post('delete',{id:id,type:'index'},function(ret) {
                alert(ret);
                window.location.reload();
            });
        }
    });
});
// 批量作废
$('.delete-lots').on('click',function() {
    var ids = $("#oa-goods").yiiGridView("getSelectedRows");
    var self = $(this);
    if(ids.length == 0) return false;
    $.ajax({
        url:$('.delete-lots').data('url'),
        type:"post",
        data:{id:ids},
        success:function(res){
            console.log("yeah lots failed!");
        }
    });
});

//认领对话
$('.data-heart').on('click',  function () {
    $('.modal-body').children('div').remove();
    $.get($('.data-heart').data('url'),  { id: $(this).closest('tr').data('key') },
        function (data) {
            $('.modal-body').html(data);
        }
    );
});

//图标剧中
$('.glyphicon-eye-open').addClass('icon-cell');
$('.wrapper').addClass('body-color');


// 查看框
$('.index-view').on('click',  function () {
    $('.modal-body').children('div').remove();
    $.get($('.index-view').data('url'),  { id: $(this).closest('tr').data('key') },
        function (data) {
            console.log(data);
            $('.modal-body').html(data);
        }
    );
});

//更新框
$('.index-update').on('click',  function () {
    $('.modal-body').children('div').remove();
    $.get($('.index-update').data('url'),  { id: $(this).closest('tr').data('key') },
        function (data) {
            $('.modal-body').html(data);
        }
    );
});

//创建框
$('.index-create').on('click',  function () {
    $('.modal-body').children('div').remove();
    $.get($('.index-create').data('url'),
        function (data) {
            $('.modal-body').html(data);
        }
    );
});


$(".upload").on('click', function () {
    var fileInput = document.getElementById('import');
//            fileInput.outerHTML = fileInput.outerHTML; //清空文件选择
    fileInput.click();
    fileInput.addEventListener('change', function () {
        if (!fileInput.value) {
            console.log("no file chosed");
            return;
        }
        var file = fileInput.files[0];
        console.log(file);
        var reader = new FileReader();
        reader.onload = function (event) {
            var csvdata = event.target.result;
            var data = $.csv.toObjects(csvdata);
            console.log(csvdata);
            $.ajax({
                url: $('.upload').data('url'),
                type: 'post',
                data: {
                    data: JSON.stringify({'data': data})
                },
                success: function (result) {
                    if (result.code) {
                        alert('上传出错，请检查上传文件！');
                    }
                    else {
                        $("[name='refresh']").click();
                        alert('上传成功！');
                    }
                }
            });
            console.log(data);
        };
        reader.readAsText(file, 'GB2312');
    });
    //fileInput.outerHTML = fileInput.outerHTML; //清空选择的文件
})


