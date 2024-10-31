(function ($) {

    /**
     * Get Page Scroll Position Percentage
     * @returns {number} scroll parentage number
     */
    function getPageScrollPercentage() {
        const documentHeight = Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
        const windowHeight = window.innerHeight;
        const scrollTop = window.scrollY || window.pageYOffset;
        const scrollPercent = (scrollTop / (documentHeight - windowHeight)) * 100;
        return Math.round(scrollPercent);
    }

    if(pixelavo_event && pixelavo_event.length > 0) {
        pixelavo_event.forEach((item) => {
            const {track, data, event, eventID, isEventDelay} = item
            if(event === 'ViewContent' && isEventDelay) {
                const {ajax_url, nonce} = pixelavo
                const {eventDelay} = item
                setTimeout(function() {
                    $.ajax({
                        url: ajax_url,
                        method: "POST",
                        data: {
                            'action': 'pixelavo_view_content_delay_server_event',
                            'nonce': nonce,
                            'data': data,
                            'event': event,
                            'event_id': eventID 
                        },
                        success: (function ({ data }) {
                            console.log('ViewContent server event run successfully');
                        })
                    })
                    fbq(track, event, data, { eventID: eventID });
                }, eventDelay * 1000)
            } else {
                fbq(track, event, data, { eventID: eventID });
            }
        })
    }

    /**
     * Other/Extra Events
     * Page Scroll Event
     */
    if(pixelavo?.other_events) {
        const other_events = JSON.parse(pixelavo?.other_events);
        const { page_scroll, page_scroll_value, time_on_page, time_on_page_value } = other_events;

        const fireOtherEvents = (name, data) => {
            const eventID = `${name}.` + new Date()?.getTime();
            fbq('trackCustom', name, data, { eventID });
            $.ajax({
                url: pixelavo.ajax_url,
                type: "POST",
                data: {
                    'action': 'pixelavo_fire_other_events',
                    'ajax_nonce': pixelavo.nonce,
                    'data': data,
                    'event_id': eventID,
                    'event_name': name,
                },
                success: (function ({success, data,}) {
                    if(success) {
                        console.log(data?.message);
                    }
                })
            })
        }
        window.fireOtherEvents = fireOtherEvents;
        
        if(page_scroll === 'on') {
            let $eventFired = false;
            $(document).on('scroll', function() {
                const scrollPosition = getPageScrollPercentage();
                if(scrollPosition >= +page_scroll_value && !$eventFired) {
                    $eventFired = true;
                    fireOtherEvents('PageScroll', other_events.data)
                }
            })
        }

        if(time_on_page === 'on') {
            setTimeout(function() {
                fireOtherEvents('TimeOnPage', other_events.data)
            }, +time_on_page_value * 1000);
        }
    }

})(jQuery); 