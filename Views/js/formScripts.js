
//AJAX JQuery

// |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
const registerPetForm = {
    limitAge: function () {
        $(document).ready(function () {

            var age = $("#age");

            // Escuchar el evento de entrada en el campo
            age.on("input", function () {
                // Verificar si la longitud del valor es mayor que 8
                if (age.val().length > 2) {
                    // Si es mayor, recortar el valor a 8 caracteres
                    age.val(age.val().slice(0, 2));
                }
            });

        });
    }
}
const registerForm = {
    limitDni: function () {
        $(document).ready(function () {
            // Obtener referencia al campo de entrada usando jQuery
            var dni = $("#dni");
    
            // Escuchar el evento de entrada en el campo
            dni.on("input", function () {
            
                // Obtener el valor actual del campo
                var value = dni.val();
                
                // Eliminar caracteres no numéricos del valor (si los hay)
                var newValue = dni.val().replace(/\D/g, '');
    
                // Establecer el valor del campo como el nuevo valor sin caracteres no numéricos
                dni.val(newValue);
    
                // Verificar si el valor es negativo
                if (value < 0) {
                    dni.val('');
                }
    
                // Verificar si la longitud del valor es mayor que 8
                if (dni.val().length > 8) {
                    // Si es mayor, recortar el valor a 8 caracteres
                    dni.val(dni.val().slice(0, 8));
                }
            });
        });
    }
};
const breedManage = {
    // Función para cargar las razas desde el archivo JSON
    loadBreed: function (typePet) {
        // Realizar una solicitud AJAX para cargar el archivo JSON correspondiente

        //Hay algo raro con el enrutamiento porque esto no deberia funcionar,pero sin embargo referencia bien a ambos...
        var url = typePet === 'dog' ? "../DAOJson/dogBreeds.json" : "../DAOJson/catBreeds.json";

        console.log(url);

        $.ajax({
            url: url,
            cache: false,
            dataType: 'json',
            success: function (data) {
                var select = $('#breed');
                select.empty(); // Limpiar las opciones anteriores

                // Agregar las nuevas opciones desde el archivo JSON
                $.each(data, function (key, value) {
                    select.append($('<option>', {
                        value: value.name || value,
                        text: value.name || value
                    }));
                });
            },
            error: function () {
                console.error('Error al cargar el archivo JSON');
            }
        });
    },
    preloadBreed: function () {
        // Cargar las razas iniciales (por defecto o según tu lógica)
        this.loadBreed('dog');

        // Manejar el cambio en el input de radio
        $('input[type=radio][name=typePet]').change(function () {
            var selected = $(this).val();
            breedManage.loadBreed(selected); // Aquí utilizamos el nombre completo del objeto breedManage para acceder a la función loadBreed
        });
    }
};


// |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||

const FormAjaxModule = {

    getPetsByOwnerType: function () {
        //Tratando de agarrar el input typePet y a partir de ahi buscar los animales del owner que correspondan
        $(document).ready(function () {
            $('#TypePet').change(function () {
                var selectedType = $(this).val(); // Obtén el tipo de mascota seleccionado

                // Realiza una solicitud AJAX al servidor para obtener las mascotas del tipo seleccionado
                $.ajax({
                    url: '../Booking/getPetsByOwnAndType', // Reemplaza con la URL de tu servidor  
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        //Es la info que pasas para que se haga la peticion en este caso el typePet
                        typePet: selectedType
                    },

                    success: function (data) {
                        // Limpiar el segundo select antes de agregar nuevas opciones
                        $('#PetCode').empty();
                        //console.log(data);
                        // Agregar las opciones de mascotas obtenidas del servidor
                        $.each(data, function (index, pet) {
                            $('#PetCode').append($('<option>', {
                                value: pet.petCode,
                                text: pet.name
                            }));
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error("Error en la solicitud AJAX:");
                        console.error("Estado: " + status);
                        console.error("Error: " + error);
                    }
                });
            });
        });

    },
    generateVisitPerDaySelect: function () {
        // Realizar la llamada AJAX para obtener el atributo visitPerDay del Keeper
        var keeperCode = $("#keeperCode").val();
        var urlMod = "../Keeper/GetVisitPerDay"
        console.log("keeperCode" + keeperCode);
                var url = window.location.href;

                // Obtener el código del cuidador de la URL
                var parts = url.split('/');
                var partCount = parts.length;
                if(partCount === 9){
                    urlMod = "../../Keeper/GetVisitPerDay"
                }
        $.ajax({
            url: urlMod, // Especifica la URL de tu endpoint para obtener visitPerDay
            method: 'POST',
            dataType: "json",
            data: {
                keeperCode: keeperCode
            }, // Envía el código del Keeper como parámetro
            success: function (response) {
                // Verifica si la respuesta contiene el atributo visitPerDay
                var visitPerDay = response; // Obtiene el valor de visitPerDay del Keeper


                // Agrega las opciones según el valor de visitPerDay
                if (visitPerDay == 1) {
                    $('#visitPerDaySelect').append('<option value="1">1</option>');
                } else if (visitPerDay == 2) {
                    $('#visitPerDaySelect').append('<option value="1">1</option>');
                    $('#visitPerDaySelect').append('<option value="2">2</option>');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error en la solicitud AJAX:', error);
            }
        });
    },

    selectYours: function () {
        var typePetCode = document.getElementById('PetCode');
        var typeSelected = document.getElementById('TypePet');

        // Agrega un evento de cambio al primer select
        typeSelected.addEventListener('change', function () {
            var typeSelectedPet = typeSelected.value;

            // Actualiza el texto del segundo select según la selección del primero
            $('#PetCode').appendChild($('<option>', {
                value: '0',
                text: `Seleccione su ${typeSelectedPet}`
            }))
            //typePetCode.options[0].text = `Seleccione su ${typeSelectedPet}`;
        });
    },

    getSpecificPets: function () {
        //Tratando de agarrar el input typePet y a partir de ahi buscar los animales del owner que correspondan
        $(document).ready(function () {
            var typePet = document.getElementById("DivType");
            var typeSize = document.getElementById("DivSize");

            var urlMod = "../Booking/getPetsByOwnFiltered"
            console.log("keeperCode" + keeperCode);
                var url = window.location.href;

                // Obtener el código del cuidador de la URL
                var parts = url.split('/');
                var partCount = parts.length;
                if(partCount === 9){
                    urlMod = "../../Booking/getPetsByOwnFiltered"
                }

            //Una es la variable interna y otra el data-*

            var dataTypePet = typePet.dataset.typepet;
            var dataTypeSize = typeSize.dataset.typesize;
            console.log(dataTypePet);
            console.log(dataTypeSize);
            // Realiza una solicitud AJAX al servidor para obtener las mascotas del tipo seleccionado
            $.ajax({
                url: urlMod, // Reemplaza con la URL de tu servidor  
                dataType: 'json',
                type: 'POST',
                data: {
                    //Es la info que pasas para que se haga la peticion en este caso el typePet
                    typePet: dataTypePet,
                    typeSize: dataTypeSize

                },

                success: function (data) {
                    // Limpiar el segundo select antes de agregar nuevas opciones
                    $('#PetCode').empty();
                    //console.log(data);
                    // Agregar las opciones de mascotas obtenidas del servidor
                    $.each(data, function (index, pet) {
                        $('#PetCode').append($('<option>', {
                            value: pet.petCode,
                            text: pet.name
                        }));
                    });
                },
                error: function (xhr, status, error) {
                    console.error("Error en la solicitud AJAX:");
                    console.error("Estado: " + status);
                    console.error("Error: " + error);
                }
            });

        });
    },
    calendarBooking: function() {
        $(document).ready(function() {
            var bookCode = $("#btnprof").data('codebook');
            // Realizar la llamada AJAX para obtener el rango de fechas
            //console.log("BookCode : "+bookCode);
            $.ajax({
                url: '../../Booking/GetIntervalBooking', // URL de tu script PHP que obtiene el rango de fechas
                method: 'POST',
                data: { bookCode: bookCode },
                dataType: 'json',
                success: function(response) {
                    console.log("SUCCESS: ", response);
                    var datesInRange = response; // Suponiendo que el servidor devuelve un arreglo de fechas en el rango
    
                    // Renderizar el calendario con las fechas en el rango
                    var calendarEl = document.getElementById('calendar');

                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        events: datesInRange.map(date => ({
                            start: date,
                            backgroundColor: 'green'
                        }))
                    });
                    
                    calendar.render();
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener el rango de fechas:', error);
                }
            });
        });
    }
};


// |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
const infoModule = {
    keepersByAvail: function () {
        $(document).ready(function () {
            //En vez de levantar cada variable por separado serializo todo si total lo necesito  y si las horas estan vacias laburo con las fechas
            $('#FilterButton').on('click', function () {
                var formData = $('#SearchForm').serialize();

                // Realiza la solicitud AJAX para filtrar los datos
                $.ajax({
                    url: '../Keeper/getFilteredKeeper', // Reemplaza con la URL de tu servidor
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        // Procesa la respuesta y muestra los resultados
                        $('#results').html(response);
                    },
                    error: function (xhr, status, error) {
                        console.error("Error en la solicitud AJAX:", error);
                    }
                });
            });
        });
    },

    bioEdit: function () {
        $(document).ready(function () {
            var userCode = $('#bio').data("userlogged");
            $('#editBioBtn').click(function () {
                $('#bio').hide();
                $('#bioEditor').show();
                $('#bioTextarea').val($('#bio').text()); //en caso que haya bio muestra el valor dentro del textarea es la actual
                $('#editBioBtn').hide();
                $('#saveBioBtn').show();
                $('#cancelBioBtn').show();
            });

            $('#cancelBioBtn').click(function () {
                $('#bio').show();
                $('#bioEditor').hide();
                $('#editBioBtn').show();
                $('#saveBioBtn').hide();
                $('#cancelBioBtn').hide();
            });

            $('#saveBioBtn').click(function () {
                var bio = $('#bioTextarea').val();
                if (bio.length > 200) {
                    alert('Bio should be max 200 characters');
                    return;
                }
                $.ajax({
                    type: 'POST',
                    url: '../Home/doBio', // Ruta del controlador para guardar la bio
                    data: { bio: bio,
                            userCode :userCode },
                    success: function(response) {
                        
                        
                        $('#bioEditor').hide();
                        $('#editBioBtn').show();
                        $('#saveBioBtn').hide();
                        $('#cancelBioBtn').hide();
                        $('#bio').show();
                        location.reload();
                       
                    },
                    error: function(xhr, status, error) {
                        console.log("error");
                    }
                });
            })
        })
    }
};

const chatModule = {

    displayAvailToTalk: function () {
        $(document).ready(function () {
            $.ajax({
                url: '../Message/getAvailTalk',
                method: 'POST',
                dataType: 'json',
                success: function (response) {
                    if(response && response.length > 0)
                        {
                    // Recorrer los datos recibidos y agregar enlaces al contenedor
                    console.log("RESPONSE :" + response);
                    var userLogged = response[0].logged;
                    console.log("userLogged :" + userLogged);
                    $.each(response, function (index, user) {
                        

                        
                        // Crear el elemento de imagen del avatar
                        if (userLogged === 'keeper') {

                            var userContainer = $('<div>', {
                                class: 'd-flex align-items-center mb-3'
                            });

                            var avatarImg = $('<img>', {
                                src: user.opfp, // Placeholder para la URL del avatar
                                alt: 'avatar',
                                class: 'rounded-circle d-flex align-self-center me-3 shadow-1-strong',
                                width: '60',
                                height: '80'
                            });

                            // Crear el contenedor de la información del usuario
                            var userInfo = $('<div>', {
                                class: 'pt-1 text-truncate'
                            });

                            var fullName = user.oname + ' ' + user.olastname
                            var username = $('<p>', {
                                class: 'fw-bold mb-0 ',
                                text: fullName // Placeholder para el nombre de usuario
                            });
                            var message = $('<p>', {
                                class: 'last-message small text-muted ',
                                text: user.lastMsgText // Placeholder para el último mensaje
                            });
                            userInfo.append(username, message);
                        } else if (userLogged === 'owner') {
                            var avatarImg = $('<img>', {
                                src: user.kpfp, // Placeholder para la URL del avatar
                                alt: 'avatar',
                                class: 'rounded-circle d-flex align-self-center me-3 shadow-1-strong',
                                width: '60',
                                height: '80'
                            });

                            // Crear el contenedor de la información del usuario
                            var userInfo = $('<div>', {
                                class: 'pt-1 text-truncate'
                            });

                            var fullName = user.kname + ' ' + user.klastname
                            var username = $('<p>', {
                                class: 'fw-bold mb-0',
                                text: fullName // Placeholder para el nombre de usuario
                            });

                            var message = $('<p>', {
                                class: 'last-message small text-muted text-truncate',
                                text: user.lastMsgText // Placeholder para el último mensaje
                            });
                            userInfo.append(username, message);

                            


                        }


                        // Crear el contenedor de la marca de tiempo
                        var timestampInfo = $('<div> ', {
                            class: 'pt-1 text-truncate'
                        });
                        var timestamp = $('<p>', {
                            class: 'infotimestamp small text-muted m-auto  ',
                            text: user.msgTimeStamp // Placeholder para la marca de tiempo
                        });

                        var badge = $('<span>', {
                            class: 'noti badge bg-danger float-end m-2',
                            text: '!' // Placeholder para el número de mensajes no leídos
                        });
                        badge.attr('data-convercode', user.codeConv);

                        if (user.unread_messages > 0) {
                            timestampInfo.append(timestamp, badge);
                        } else {
                            timestampInfo.append(timestamp);
                        }


                        // Crear el contenedor de la conversación
                        var conversationLink = $('<a>', {
                            href: '#',
                            class: 'd-flex justify-content-between availTalkOption',
                            'data-convercode': user.codeConv // Placeholder para el código de conversación
                        });


                        conversationLink.append(avatarImg, userInfo, timestampInfo);

                        $('#colAvail').append($('<li>', {
                            class: 'p-2 border-bottom',
                            style: 'background-color: #eee;'
                        }).append(conversationLink));
                    
                    });
                }
                },
                error: function (xhr, status, error) {
                    console.error('Error al obtener usuarios disponibles:', error);
                }
            });
        });
    },
    selectChats: function () {
        $(document).ready(function () {

            // Manejar el clic en una conver
            //No estoy seguro que off,on esten funcionando como deberia!
            $(document).off('click', '.availTalkOption').on('click', '.availTalkOption', function (event) {

                event.preventDefault();

                var chatCode = $(this).data('convercode');
                $('#messageSection').show();
                $("#sendMsg").attr("data-convercode", chatCode);
                var sendMsg = $("#sendMsg").data("convercode");

                var dataArray = {
                    //"senderCode" : sender,
                    //"receiverCode" : receiverCode,
                    "converCode": chatCode
                };

                // Vaciar el contenido del contenedor ul antes de cargar nuevos mensajes
                $('#chatDef').empty();
                // Realizar una solicitud AJAX para cargar los mensajes asociados con el chatId
                $.ajax({
                    url: '../Message/getMessages',
                    method: 'POST',
                    data: dataArray,
                    success: function (response) {

                        $('#chat').html(response);
                        $("#sendMsg").data("convercode", chatCode);

                        var responseObj = JSON.parse(response);

                        var currentUserCode = responseObj.currentUserCode;




                        //Itera sobre los mensajes y genera el HTML correspondiente
                        for (var key in responseObj) {
                            if (responseObj.hasOwnProperty(key)) {
                                if (key !== 'currentUserCode') { //Evitamos que el currentUser entre como mensaje erroneo
                                    var msgeInfo = responseObj[key];
                                    // Determinar la clase CSS para el contenedor del mensaje
                                    var messageAlignmentClass = msgeInfo.codeSender === currentUserCode ? 'justify-content-end' : 'justify-content-start';
                                    var messageDirectionClass = msgeInfo.codeSender === currentUserCode ? 'bg-warning text-start' : 'bg-success text-start';

                                    // Crear el HTML para mostrar el mensaje en el chat
                                    var messageHtml = '<li class="d-flex mb-4 ' + messageAlignmentClass + '">' +
                                        '<div class="card ' + messageDirectionClass + '">' +
                                        '<div class="card-header">' +
                                        '<p class="large-text text-dark">' + msgeInfo.msgText + '</p>' +
                                        '</div>' +
                                        '<div class="card-body">' +
                                        '<p class="small text-muted mb-0"><i class="far fa-clock"></i>' + msgeInfo.timestamp + '</p>' +
                                        '</div>' +
                                        '</div>' +
                                        '</li>';
                                    $('#chatDef').append(messageHtml);

                                }
                            }
                        }

                        // Obtén el elemento scrollable (por ejemplo, un div con un contenido largo)
                        var scrollableElement = document.getElementById('chatDef');

                        // Establece la propiedad scrollTop al máximo para que se vea desde el final
                        scrollableElement.scrollTop = scrollableElement.scrollHeight;

                        // $('.chatColumn').each(function () { //Iterar sobre cada chatColumn generado por el php
                        //     var $noti = $(this).find('.noti'); //Tomo
                        //     if ($(this).data('convercode') === dataArray.chatCode) {
                        //         $(this).remove();
                        //     }
                        // })

                        var selectedConverCode = chatCode;
                        $('.noti').each(function () {
                            if ($(this).data('convercode') === selectedConverCode) {
                                $(this).remove(); // Eliminar la notificación
                            }
                        });

                    },
                    error: function (error) {
                        console.error('Error al cargar mensajes: ', error);
                    }
                });
            });
        });
    },
    sendMsg: function () {

        $('#sendMsg').click(function (event) { //Importante si hago busqueda de datos revisar que tags son child y cuales ancestor respecto al tag de origen de la funcion


            event.preventDefault();

            var message = $("#msg").val();
            var chatCode = $("#sendMsg").data("convercode");
            console.log("MESSG" + message);


            // los "xxxx" tienen que corresponder con lo que reciba el controller
            var dataArray = {
                "msgText": message,
                "converCode": chatCode
            };

            console.log(dataArray.message);
            console.log(dataArray.chatCode);


            //Quiza el controlador/funciones deberian volver el mensaje como validacion que se envio entonces uso el response y clavo el msje
            $.ajax({
                url: '../Message/sendMessage',
                method: 'POST',
                data: dataArray,
                success: function (response) {

                    $("#msg").val("");


                    // Analiza la respuesta JSON
                    var responseMsge = JSON.parse(response);


                    // $('.chatColumn').each(function () { //Iterar sobre cada chatColumn generado por el php
                    //     var lastMsg = $(this).find('.lastMsg'); //Tomo
                    //     if ($(this).data('convercode') === dataArray.chatCode) {
                    //         lastMsg.data('lastMsg', message).text(message);
                    //     }
                    // });


                    // Actualiza dinámicamente el recuadro de texto de la lista de columnas de contactos
                    $('.last-message').each(function () {
                        var conversationCode = $(this).closest('.availTalkOption').data('convercode');
                        var timestamp = $(this).closest('.availTalkOption').find('.infotimestamp');
                        if (conversationCode === chatCode) {
                            $(this).text(message);
                            timestamp.text(responseMsge.timeStamp); // Actualiza el timestamp
                        }
                    });
                    var messageHtml = '<li class="d-flex justify-content-end mb-4">' +
                        '<div class="card bg-warning text-end">' +
                        '<div class="card-header d-flex justify-content-between">' +
                        '<p class="large-text text-dark">' + responseMsge.msgText + '</p>' +
                        '</div>' +
                        '<div class="card-body">' +
                        '<p class="small text-muted mb-0"><i class="far fa-clock"></i>' + responseMsge.timeStamp + '</p>' +
                        '</div>' +
                        '</div>' +
                        '</li>';

                    // $('#chatDef').append(messageHtml);

                    //     // Hacer scroll hacia abajo
                    //     var chatDef = document.getElementById('chatDef');
                    //     chatDef.scrollTop = chatDef.scrollHeight;

                    chatModule.loadChats(dataArray.converCode);
                },
                error: function (error) {
                    // Manejar errores
                    console.error(error);
                }
            });

        });
    },

    loadChats: function (converCode) {
        // Realizar una solicitud AJAX para cargar los mensajes de la conversación
        $.ajax({
            url: '../Message/getMessages', // Reemplaza esto con la URL correcta en tu aplicación
            method: 'POST',
            data: {
                "converCode": converCode
            },
            success: function (updatedChat) {
                // Mostrar los mensajes en el div de chat
                $('#chat').html(updatedChat);
            },
            error: function (error) {
                console.error('Error al cargar mensajes: ', error);
            }
        });
    }


};

const KeepersInteract = {
    calendarKeeper: function() {
        $(document).ready(function() {
            var urlToSend = '../Keeper/GetIntervalDates';
            // var baseUrl = $('#contMain').data('baseurl');
            // var baseUrl = baseUrl + 'Keeper/getAvailability';urlToSend
            var keeperCode = $("#btnprof").data('codekeeper');
            if(keeperCode === undefined)
            {
                var url = window.location.href;

                // Obtener el código del cuidador de la URL
                var parts = url.split('/');
                var keeperCode = parts[parts.length - 1];
                urlToSend = '../../Keeper/GetIntervalDates';
            }

            console.log(keeperCode);
            // Realizar la llamada AJAX para obtener el rango de fechas
            $.ajax({
                url: urlToSend, // URL de tu script PHP que obtiene el rango de fechas
                method: 'POST',
                data: { keeperCode: keeperCode },
                dataType: 'json',
                success: function(response) {
                    console.log("SUCCESS: ", response);
                    var datesInRange = response; // Suponiendo que el servidor devuelve un arreglo de fechas en el rango
    
                    // Renderizar el calendario con las fechas en el rango
                    var calendarEl = document.getElementById('calendar');
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        events: datesInRange.map(date => ({
                            start: date,
                            backgroundColor: 'green'
                        }))
                    });
    
                    calendar.render();
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener el rango de fechas:', error);
                }
            });
        });
    },
    displayEditDates: function () {
        $(".btn-edit").click(function (e) {
            e.preventDefault();
            var row = $(this).closest("tr");
            var initDate = row.find("td:first").text().trim();
            var endDate = row.find("td:nth-child(2)").text().trim();
    
            // Reemplaza el contenido de las celdas con inputs para editar
            row.find("td:first").html('<input type="date" class="form-control initial-date-input" value="' + initDate + '">');
            //Selector for 2nd td in tr
            row.find("td:nth-child(2)").html('<input type="date" class="form-control end-date-input" value="' + endDate + '">');
            // Oculta el botón de editar
            row.find(".btn-edit").hide();
            // Muestra los botones de guardar y cancelar
            row.find(".btn-save, .btn-cancel").show();
        });
    },
    
    updateDates: function () {
        $(".btn-save").click(function () {
            var row = $(this).closest("tr");
            var initDate = row.find(".initial-date-input").val();
            var endDate = row.find(".end-date-input").val();
            
            
            if (!initDate || !endDate) {
                // Mostrar mensaje de error
                $("#result-message").text("Please,complete both fields").show();
                return; // Detener la ejecución
            }
            // Aquí puedes enviar los datos actualizados al servidor usando AJAX
            $.ajax({
                url: '../Keeper/updateAvailability', // Ruta al controlador
                type: 'POST',
                data: {
                    initDate: initDate,
                    endDate: endDate
                },
                dataType: 'json',
                success: function (response) {
                    console.log("Actualización exitosa");
                    console.log("response: " + response);
                    
                    // Recarga la página automáticamente para que se muestre el cambio
                    
                    if(response != 1){
                        $("#result-message").removeClass("alert alert-success").addClass("alert alert-danger").text("Invalid initial date.").show();
                    }else{
                        location.reload();
                        $("#result-message").removeClass("alert alert-danger").addClass("alert alert-success").text("Dates updated.").show();
                    }
                },
                error: function (xhr, status, error) {
                    // Manejar errores de la petición AJAX
                    console.log("Error:", error + "Status :", status + "xhr :", xhr);
                }
            });
        });
    
        $(".btn-cancel").click(function () {
            // Recarga la página para cancelar la edición y mostrar los datos originales
            location.reload();
        });
    },
    

    getKeeperAvail: function () {
        $(document).ready(function () {
            $('.btn-availability').click(function (e) {
                e.preventDefault();
                //Como no paraba de arrojar errores segun el controlador que llamaba a la vista,tuve que forzar el enrutamiento para tener acceso al Keeper/getAvailability independientemente de donde este parado (la url)
                var baseUrl = $('#contMain').data('baseurl');
                var btn = $(this);
                var card = btn.closest('.card');
                var additionalInfo = card.find('.additional-info');
                var codeKeeper = btn.data('codekeeper'); // Obtener el código del guardián desde el atributo data
                console.log("CODEKEP"+ codeKeeper);
                //     // Definir la URL base
                var baseUrl = baseUrl + 'Keeper/getAvailability';

                // Verificar si estamos en una vista diferente que requiere ajuste en la URL
                //Si no aparece Home asumimos que esta en el index mas inicial de todos
                // if (window.location.pathname.indexOf('Home') !== -1) {
                //     baseUrl = '../' + baseUrl; // Ajustar la URL según la vista
                // }
               
                console.log("URL" + baseUrl);
                $.ajax({
                    
                    url: baseUrl, // Ruta controller
                    method: 'POST',
                    data: {
                        "keeperCode" : codeKeeper
                    }, // Pasar el código del guardián como parámetro
                    dataType: 'JSON',
                    
                    success: function (response) {
                        console.log(response);
                        var html = '<div class="container my-2 text-white ">';
                        html += '<ul class="list-unstyled text-center">';
                        html += '<div class="mx-auto">';
                        html += '<h5 class="max-width-100 text-truncate">' + "AVAILABILITY :" + '</h5>';
                        html += '<li class="list-group-item list-group-item-info rounded  ">';
                        html += '<p><strong>START :</strong> ' + response.initDate + '</p>';
                        html += '<p><strong>END :</strong> ' + response.endDate + '</p>';
                        html += '</div>';
                        html += '</ul>';
                        html += '</div>';

                        //console.log(html);
                        additionalInfo.html(html);
                        additionalInfo.css('visibility', 'visible');
                        // Mostrar la tarjeta adicional
                        additionalInfo.show();
                        additionalInfo.append('<button type="button" class="d-inline btn close-btn bg-danger text-white">X</button>');
                        // Manejar el evento de cierre de la nueva carta
                        additionalInfo.find('.close-btn').click(function () {
                            // Mostrar la carta original

                        // Ocultar la nueva carta
                            additionalInfo.hide();
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error('Error al cargar el contenido:', error);
                        console.log("SOY EL XHR :" + JSON.stringify(xhr));
                        console.log("SOY EL STATUS :" + status);
                    }
                });
            });
        });
    },

    // displayEditHours: function () {
    //     $(document).ready(function () {
    //         $(".update-hours-btn").click(function (e) {
    //             e.preventDefault();
    //             var row = $(this).closest("tr");
    //             row.find(".initial-hour-input").val(row.find("p:first").text().trim()).show();
    //             row.find(".end-hour-input").val(row.find("p:last").text().trim()).show();
    //             row.find(".update-hours-btn").hide();
    //             row.find(".save-hours-btn").show();
    //         })
    //     })
    // },
    // updateHours: function () {
    //     $(document).ready(function () {
    //         $(".save-hours-btn").click(function () {

    //             // Encuentra el elemento ascendente más cercano que coincide con el selector
    //             var row = $(this).closest("tr");
    //             // Encuentra los valores de las horas de inicio y fin
    //             var initHour = row.find(".initial-hour-input").val();
    //             var endHour = row.find(".end-hour-input").val();
    //             // Obtiene el valor del atributo "data-idAvail"
    //             var idAvail = $(this).data("idavail");

    //             console.log("InitHour" + initHour);
    //             console.log("endHour" + endHour);
    //             console.log("idAvail" + idAvail);
    //             // Aquí puedes enviar los datos actualizados al servidor usando AJAX
    //             $.ajax({
    //                 url: '../Keeper/updateHours', // Ruta al controlador
    //                 type: 'POST',
    //                 data: {
    //                     idAvail: idAvail, // Envía el idAvail como parte de los datos
    //                     initHour: initHour,
    //                     endHour: endHour
    //                 },
    //                 success: function (response) {

    //                     console.log("Actualización exitosa");
    //                     console.log("response" + response);
    //                     // Recarga la pagina automatica para que displayee el cambio
    //                     location.reload();
    //                     //location.reload();
    //                 },
    //                 error: function (xhr, status, error) {
    //                     // Manejar errores de la petición AJAX
    //                     console.log("Error:", error + "Status :", status + "xhr :", xhr);
    //                 }
    //             });
    //         });
    //     });
    // },
    scrollingKeepers: function () {
        $(document).ready(function () {
            var loading = false;
            var startIndex = 10; // Comienza desde donde termina el lote inicial
            var batchSize = 6; // Cantidad de resultados a cargar por lote

            // Función para cargar más datos
            function loadMoreData() {
                loading = true;
                $.ajax({
                    url: '../Owner/showMoreData', // Cambia 'load-more-data.php' por el archivo que maneja la carga de más datos
                    method: 'GET',
                    data: {
                        startIndex: startIndex,
                        batchSize: batchSize
                    },
                    success: function (response) {
                        $('#data-container').append(response);
                        startIndex += batchSize;
                        loading = false;
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                        loading = false;
                    }
                });
            }

            // Detectar scroll y cargar más datos si es necesario
            $(window).scroll(function () {
                if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100 && !loading) {
                    loadMoreData();
                }
            });
        });
    },

    reConfirm: function() {
        $(document).ready(function() {
            $('.btn-dis').click(function(event) {
                var message = $(this).data('msg');
                if (!confirm(message)) {
                    event.preventDefault();
                }
            });
        });
    }
    
};

const cardFuncs = {

    onlyNumberInput: function () {
        $(document).ready(function () {
            // Función para aplicar la lógica de números solo a los campos de entrada especificados
            function applyNumericInput(selector) {
                $(selector).on('input', function () {
                    // Eliminar caracteres no numéricos
                    $(this).val($(this).val().replace(/\D/g, ''));
                });
            }

            // Aplicar la lógica a los campos de entrada deseados
            applyNumericInput('#ccnum,#expDate');
        });
    },
    manageExpire: function () {
        $(document).ready(function () {
            $('#expDate').on('input', function () {
                // Aplicar esto .replace(/\D/g, '') a los campos para que sea solo numerico,ver si se puede combinar con el regex del replace anterior
                var trimmedValue = $(this).val().replace(/[^\d\/]/g, ''); // Eliminar espacios en blanco
                var formattedValue = trimmedValue.replace(/(\d{2})(\/)?(\d{0,2})/, function (match, p1, p2, p3) {
                    // Formatear la entrada como "MM/YY"
                    if (p2 !== undefined) {
                        return p1 + (p3 !== '' ? '/' + p3 : '');
                    } else {
                        return p1 + (p1.length === 2 ? '/' : '');
                    }
                });
                $(this).val(formattedValue);
            });
        });
    },

    manageCardNumb: function () {
        $(document).ready(function () {

            $('#ccnum').on('input', function () {
                var creditCardNumber = $(this).val().replace(/\D/g, ''); // Eliminar caracteres no numéricos
                var formattedNumber = creditCardNumber.replace(/(\d{4})(?=\d)/g, '$1 '); // Aplicar espaciado cada 4 dígitos

                // Limitar la longitud a 19 caracteres (16 dígitos + 3 espacios)
                if (formattedNumber.length > 19) {
                    formattedNumber = formattedNumber.substr(0, 19);
                }

                $(this).val(formattedNumber);
            });
        });
    },

    manageCcv: function () {
        $(document).ready(function () {
            $('#ccv').on('input', function () {
                var ccvNumber = $(this).val().replace(/\D/g, ''); // Eliminar caracteres no numéricos
                ccvNumber = ccvNumber.substring(0, 3); // Limitar a 3 caracteres
                $(this).val(ccvNumber);
            });
        });

    },

    manageCardHolder: function () {
        $(document).ready(function () {
            $('#cholder').on('input', function () {
                var sanitizedValue = $(this).val().replace(/[^a-zA-Z\s'-]/g, ''); // Permitir solo letras, espacios, guiones y apóstrofes
                var sanitizedInput = sanitizedValue.replace(/[-!$%^&*()_+|~=`{}\[\]:";'<>?,.\/]/g, ' ');

                if (sanitizedInput.length > 50) {
                    sanitizedInput = sanitizedInput.substr(0, 50);
                }
                $(this).val(sanitizedInput);
            });
        });
    }
};


const moduleReview = {
    displayFieldReview: function() {
        $(document).ready(function() {
            $("#rateBtn").click(function() {
                $("#reviewPopup").css("display", "block");
                
            });

            

            $(".close").click(function() {
                $("#reviewPopup").css("display", "none");
            });

            // Ocultar la ventana emergente cuando se hace clic fuera de ella
            $(window).click(function(event) {
                if (event.target == $("#reviewPopup")[0]) {
                    $("#reviewPopup").css("display", "none");
                }
            });

            
            $("#submitReview").click(function() {
                var comment = $("#reviewText").val();
                var score = $("#rating").val();
                var keeperCode = $("#rateBtn").data("keepercode");
                // var currentURL = window.location.href;
                // console.log("URL actual:", currentURL);
                // Aquí puedes realizar una solicitud AJAX para enviar la reseña y la puntuación al servidor
                // Aquí hay un ejemplo básico de cómo hacerlo
                $.ajax({
                    type: "POST",
                    url: "../../Review/doReview",
                    data: {  keeperCode : keeperCode,comment: comment, score: score , },
                    success: function(response) {
                        // console.log("URL de la solicitud AJAX:", this.url);
                        // Manejar la respuesta del servidor aquí
                        console.log("REPSONSE" +response);
                        // Cerrar la ventana emergente después de enviar la revisión
                        $("#reviewPopup").css("display", "none");
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Manejar errores de solicitud AJAX aquí
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    }

}
