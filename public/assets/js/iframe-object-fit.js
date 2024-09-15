const iframeObjectFit = (frameElement, width, load) => {

    const defaultWidth = width ?? 575;

    setTimeout(() => {

        const frameDocument = frameElement.contentDocument || frameElement.contentWindow.document;

        const iframeHtml = frameDocument.documentElement.querySelector('html');
        const iframeBody = frameDocument.documentElement.querySelector('body');

        let iframeBodyStyle = `position: absolute !important; top: 0% !important; left: 0% !important; width: ${defaultWidth}px !important; transform-origin: top left !important; overflow: hidden;`;

        iframeBody.style = iframeBodyStyle;

        let calu_w_d = 0;
        let calu_w = ((frameElement.clientWidth - calu_w_d) * 100) / defaultWidth;
        calu_w = calu_w / 100;
        calu_w = calu_w.toFixed(2);

        iframeBody.style = iframeBodyStyle += `transform: translate(0%, 0%) scale(${calu_w}, ${calu_w});`;
        
    }, load ?? 1000);

};

// const iframeObjectFit = (iframes, load) => {

//     const defaultWidth = 920;

//     for(let frameElement of iframes) {
//         setTimeout(() => {

//             const frameDocument = frameElement.contentDocument || frameElement.contentWindow.document;

//             const iframeHtml = frameDocument.documentElement.querySelector('html');
//             const iframeBody = frameDocument.documentElement.querySelector('body');

//             let iframeBodyStyle = `position: absolute !important; top: 0% !important; left: 0% !important; width: ${defaultWidth}px !important; transform-origin: top left !important; overflow: hidden;`;

//             iframeBody.style = iframeBodyStyle;

//             let calu_w_d = 0;
//             let calu_w = ((frameElement.clientWidth - calu_w_d) * 100) / defaultWidth;
//             calu_w = calu_w / 100;
//             calu_w = calu_w.toFixed(2);

//             iframeBody.style = iframeBodyStyle += `transform: translate(0%, 0%) scale(${calu_w}, ${calu_w});`;
            
//         }, load ?? 1000);
//     };

// };