$(function() {

// 各日にちの枠内をダブルクリックするとタスク追加部品が出現（既にこれが存在すれば消してから出現）
    $('td').dblclick(function(){
      var e = $(
        "<p>"+
         "<input type='text' class='title'>"+
         "<input type='button' class='addTask' value='add'>"+
        "</p>"
      );
        $(this).children('p').remove();
        (e).insertAfter($(this).children('span'));
        document.querySelector('.title').focus();
    });

// タスク追加部品のフォーカスが外れるとそれは消える
    $(document).on('blur','.title',function(){
            setTimeout(function(){
              $('.title').parent().remove();
      },1000);
    });

    // 追加ボタンをクリックするとタスクが追加される。
    $(document).on('click','.addTask', function() {
       var id = $(this).parent().parent().attr("id");
       var title = $(this).prev().val();

       $.post('_ajax_add_task.php', {
           id: id,
           title: title
       }, function(rs) {
           var e = $(
               '<li id="task_'+rs+'" data-id="'+rs+'">' +
               '<input type="checkbox" class="checkTask"> ' +
               '<span></span> ' +
               '<span class="editTask">[編集]</span> ' +
               '<span class="deleteTask">[削除]</span> ' +
               '<span class="drag">[drag]</span>' +
               '</li>'
           );
           $('#'+id).children('ul').append(e).find('li:last span:eq(0)').text(title);
           $('#'+id).children('p').children('input').val('').focus();
       });
   });

   // 編集ボタンをクリックするとタスク内容を書き換えられる
   $(document).on('click', '.editTask', function() {
      var i = $(this).parent().parent().attr('id');
      var id = $(this).parent().data('id');
      var title = $(this).prev().text();
      $(this).parent()
          .empty()
          .append($('<input type="text">').attr('value',title))
          .append('<input type="button" value="uppdate" class="updateTask">');
      $('#'+i+' #task_'+id+' input:eq(0)').focus();
  });

  // 更新ボタンをクリックするとタスク内容が更新される
   $(document).on('click', '.updateTask', function() {
      var i = $(this).parent().parent().parent().attr('id');
      var id = $(this).parent().data('id');
      var title = $(this).prev().val();

      $.post('_ajax_update_task.php', {
          i: i,
          id: id,
          title: title
      }, function(rs) {
          var e = $(
              '<input type="checkbox" class="checkTask"> ' +
              '<span></span> ' +
              '<span class="editTask">[編集]</span> ' +
              '<span class="deleteTask">[削除]</span> ' +
              '<span class="drag">[drag]</span>'
          );
          $('#'+i+' #task_'+id).empty().append(e).find('span:eq(0)').text(title);
      });
  });

  // タスク並べ替え
    $("ul").each(function() {
       var id = $(this).attr('id');
    $('#'+id).sortable({
       axis: 'y',
       opacity: 0.2,
       handle: '.drag',
       update: function() {
           $.post('_ajax_sort_task.php', {
               id:  $(this).parent().attr('id'),
               task: $(this).sortable('serialize')
           });
       }
   });
  });

      // チェックボックスをクリックするとタスクの完了状況を変更できる
      $(document).on('click', '.checkTask', function() {
        var i = $(this).parent().parent().parent().attr('id');
        var id = $(this).parent().data('id');
        var title = $(this).next();
        $.post('_ajax_check_task.php', {
             i: i,
            id: id
        }, function(rs) {
            if (title.hasClass('done')) {
                title.removeClass('done').next().addClass('editTask');
            } else {
                title.addClass('done').next().removeClass('editTask');
            }
        });
    });

    // 削除ボタンをクリックするとタスクが削除される
       $(document).on('click', '.deleteTask', function() {
         if (confirm('本当に削除しますか？')) {
             var i = $(this).parent().parent().parent().attr('id');
             var id = $(this).parent().data('id');
             $.post('_ajax_delete_task.php', {
                 i: i,
                 id: id
             }, function(rs) {
                 $('#task_'+id).fadeOut(800);
             });
         }
   });

});
