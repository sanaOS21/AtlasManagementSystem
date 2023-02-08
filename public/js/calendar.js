$(function () {
  $('.cancel-modal-open').on('click', function () {
    $('.js-modal').fadeIn();
    // 変数に要素を入れる
    var delete_date = $(this).attr('delete_date');
    var delete_part = $(this).attr('delete_part');
    $('.cancel-modal-hidden').val(delete_date);
    $('.cancel-modal-date').text(delete_date);
    $('.cancel-modal-part').text(delete_part);
    return false;
  });

  $('.js-modal-close').on('click', function () {
    $('.js-modal').fadeOut();
    return false;
  });
});
