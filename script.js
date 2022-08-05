function hideField (checked) {
    if(checked == true)
        $("#sumAddField").fadeIn();
        else $("#sumAddField").fadeOut();
}

$(function() {    
    new AirDatepicker('#startDate');
});

$().ready(function() {
      
    $('#mory').on('change', function() {
        if ($(this).val() == "month") {
            $('#term').rules('add', {
                range: [1, 60]
            })
        } else if ($(this).val() == "year") {
            $('#term').rules('add', {
                range: [1, 5]
            })            
        } else {
            $('#term').rules('remove');
        }
    });

    $('form').validate({

        rules: {
            startDate: {
                required: true,
                date: true
            },
            term: {
                required: true,
            },
            sum: {
                required: true,
                range: [1000, 3000000]
            },
            percent: {
                required: true,
                digits: true,
                range: [3, 100]
            },
            sumAdd: {
                range: [0, 3000000]
            }
        },

        messages: {
            startDate: "Выберите дату открытия",
            sum: {
                required: "Укажите сумму вклада",
                range: "Не менее 1000 и не более 3000000"
            },
            percent: {
                required: "Укажите процентную ставку",
                range: "Не менее 3 и не более 100",
                digits: "Процент не должен быть дробным"
            },
            term: {
                required: "Укажите срок вклада",
                range: "Не более 60 месяцев или 5 лет"
            },
            sumAdd: {
                range: "Не более 3000000"
            }
        }
    })
});

$(document).ready(function() {

    const formEl = document.querySelector('form');

    $('form').submit(function(event) {
     
        event.preventDefault();
        
        const formData = new FormData(formEl);
        const data = Object.fromEntries(formData);

        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: data,            
            dataType: "json",            
            success: function(result) {
                $('#result').html("₽ " + result.sum);
                $('#resultBlock').removeClass('hidden');
            },
        });        
    });
});
