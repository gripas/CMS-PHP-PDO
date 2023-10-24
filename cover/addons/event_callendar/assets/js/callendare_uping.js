/* 
* Indicates in the JS files where to present the libraries in the document.
* Important! indicate where to broadcast the head or footer, necessarily between the comments
*/
/*footer*/




function convertMinutesToHoursMinutes(minutes) {
    const hours = Math.floor(minutes / 60);
    const remainingMinutes = minutes % 60;
    if (hours > 0 && remainingMinutes > 0) {
        return `${hours} val. ${remainingMinutes} min.`;
    } else if (hours > 0 && remainingMinutes === 0) {
       
        if (remainingMinutes === 0) {
            return `${hours} val.`;
        } else { 
            return `${hours} val. ${remainingMinutes} min.`;
        }
    } else {
        return `${minutes} min.`;
    }
}
function renderTimeOptions(timeOptions, eventDate, reserveDayOption, reserveDayTimeOption) {
    let optionsHTML = '';
    if (Array.isArray(reserveDayOption) && reserveDayOption.includes(eventDate)) {
        if (timeOptions.length === 0 ) {
            return 'visi laikai užimti';
        }
        Object.values(timeOptions).forEach(time => {
            optionsHTML += `
                ${time} <input type="checkbox" class="time-checkbox" name="" value="${time}"/>;  
            `;
        });
    } else {  
        Object.values(reserveDayTimeOption).forEach(time => {
            optionsHTML += `
                ${time} <input type="checkbox" class="time-checkbox" name="" value="${time}"/>;   
            `; 
        });
    }
    return optionsHTML;
}

    

    function createEventModal(themeId, registrationAllowed, eventDate) {
        let accordionHTML = '';
        eventTopics.forEach(topic => {
            if (topic.theme_id === themeId) {
              
                const timeString = convertMinutesToHoursMinutes(topic.timeLimit);
                let reserveDayTimeOption = topic.reserveEventDay;
                let reserveDayOption = topic.reserveDay;
                let timeOptions = topic.provideTime;
              
               
                let timeOptionsHTML = renderTimeOptions(timeOptions, eventDate, reserveDayOption, reserveDayTimeOption); // 60 eilutė
           

            accordionHTML += `
                <button class="callendar-accordion accordion">${topic.title} <i class="fa fa-angle-down float-right"></i></button>
                <div class="panel">
                    <div class="flex-container">
                        <div class="flex-item-left">
                            <p><b>Trukmė:</b> ${timeString} <br><b>Amžiaus grupė:</b> ${topic.targetAudience} kl.</p>
                        </div>
                        <div class="flex-item-right">
                        <p><b>Prieinamas laikas: </b><br>
                        ${timeOptionsHTML}</p>
                    </div>
                    </div>
                    <img src="${topic.imageUrl}" class="accordion-event-image" alt="image">
                    <p>${topic.shortDescription} <br>
                    <b>Metodinė medžiaga:</b> <br>${topic.methodicalMaterial}</p>
                </div>`;

                    }
                });
                

    const modalHTML = `
    <div id="eventModal" class="modal-view">
        <div class="modal-content">
            <span class="close-event" style="text-align: right;">&times;</span>
            <h3 id="eventTitle"></h3>
            <p id="themeId" style="display:none"></p>
            <p id="eventDate"></p>
            ${accordionHTML}
        </div>
    </div>`;

    document.body.insertAdjacentHTML('beforeend', modalHTML);

    const acc = document.getElementsByClassName("accordion");
    Array.from(acc).forEach((accordion) => {
        accordion.addEventListener("click", function(e) {
            if (!registrationAllowed) {
                return;
            }

            e.stopPropagation();
            let icon = this.querySelector('.fa');
            if (icon.classList.contains('fa-angle-down')) {
                icon.classList.remove('fa-angle-down');
                icon.classList.add('fa-angle-up');
            } else {
                icon.classList.remove('fa-angle-up');
                icon.classList.add('fa-angle-down');
            }  
            if (this.classList.contains('button-accordion-color-open')) {
                this.classList.remove('button-accordion-color-open');
                this.classList.add('button-accordion-color-closed');
            } else {
                this.classList.add('button-accordion-color-open');
                this.classList.remove('button-accordion-color-closed');
            }

            Array.from(acc).forEach((otherAccordion) => {
                if (otherAccordion !== this) {
                    let otherPanel = otherAccordion.nextElementSibling;
                    otherPanel.style.display = "none";
                    otherAccordion.classList.remove("active");
                }
            });

            this.classList.toggle("active");
            let panel = this.nextElementSibling;
            if (panel.style.display === "block") {
                panel.style.display = "none";
            } else {
                panel.style.display = "block";
            }
        });
    });
}

function closeEventModal() {
    const modal = document.getElementById("eventModal");
    if (modal) {
        modal.remove();
    }
}

function showEventModal(eventData,topic) {
    const currentDate = new Date();
    const eventDate = new Date(eventData.date);
    let registrationAllowed = true;

    // Sukuriamas naujas Date objektas, kuris yra 2 dienos anksčiau už renginio datą
    const registrationEndDate = new Date(eventDate);
    let endRegister = topic ? topic.setRegistrationEndDate : 0;

    registrationEndDate.setDate(registrationEndDate.getDate() - endRegister);

    if (registrationEndDate <= currentDate) {
        eventData.title += ' <span class="span-title" style="color: #d55258;">(Registracija pasibaigusi)</span>';
        registrationAllowed = false;
    }

    createEventModal(eventData.id, registrationAllowed, eventData.date);

    const modal = document.getElementById("eventModal");
    const closeBtn = document.getElementsByClassName("close-event")[0];
    const eventTitleElement = document.getElementById("eventTitle");
    const eventDateElement = document.getElementById("eventDate");
    const eventIdElement = document.getElementById("themeId");

    eventTitleElement.innerHTML = "Laboratorija: " + eventData.title;
    eventDateElement.innerText = "Pasirinkta data: " + eventData.date;
    eventIdElement.innerText = "" + eventData.id;

    modal.style.opacity = 0;
    modal.style.pointerEvents = "none";

    setTimeout(() => {
        modal.style.opacity = 1;
        modal.style.pointerEvents = "auto";
    }, 10);

    closeBtn.onclick = function() {
        closeEventModal();
    };

    window.onclick = function(event) {
        if (event.target === modal) {
            closeEventModal();
        }
    };
}

function attachEventListeners() {
    $('.event-view').off('click').click(function(e) {
        e.stopImmediatePropagation();
        const eventData = {
            id: $(this).data('theme-id'),
            title: $(this).data('title'),
            date: $(this).data('date'),
            color: $(this).data('color')
        };
        
        // Randa tinkamą topic pagal theme-id
        const matchingTopic = eventTopics.find(topic => topic.theme_id === eventData.id);
        
        showEventModal(eventData, matchingTopic);
    });
}

$(document).on('change', '.time-checkbox', function() {
    $('.time-checkbox').not(this).prop('checked', false);  
});

$(document).ready(function() {
    attachEventListeners();
    $('.calendar').click(function(e) {
        e.stopImmediatePropagation();
        $('#eventModal').hide();
        closeEventModal();
    });
});