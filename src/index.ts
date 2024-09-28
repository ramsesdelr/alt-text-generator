declare const ajax_object: {
    ajax_url: string;
};
/**
 * Captures the Attachtment ID
 * @param attachment_id 
 */
function process_image(attachment_id: number): void {
    const data = new FormData();
    data.append('action', 'update_image_alt'); // The action hook
    data.append('attachment_id', attachment_id.toString() );   //converted the ID to string since that's FormData expects string values.   

    fetch( ajax_object.ajax_url, {
        method: 'POST',
        body: data, 
    })
    .then(response => response.text()) // Convert the response to text
    .then(result => {
        alert('Server response: ' + result);
    })
    .catch(error => {
        console.error('Error:', error);
    });
} 

(window as any).process_image = process_image;