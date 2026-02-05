import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';

// 1. Filtro para a Interface (O que você já fez)
const withCarouselControl = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
        if ( props.name !== 'core/gallery' ) return <BlockEdit { ...props } />;
        const { attributes, setAttributes } = props;
        return (
            <>
                <BlockEdit { ...props } />
                <InspectorControls>
                    <PanelBody title="Configurações de Carrossel">
                        <ToggleControl
                            label="Ativar Modo Carrossel"
                            checked={ !! attributes.isCarousel }
                            onChange={ ( val ) => setAttributes( { isCarousel: val } ) }
                        />
                    </PanelBody>
                </InspectorControls>
            </>
        );
    };
}, 'withCarouselControl' );

addFilter( 'editor.BlockEdit', 'meu-projeto/gallery-carousel-control', withCarouselControl );

// 2. NOVO: Filtro para injetar a classe no HTML final (Front-end)
const addCarouselClass = ( extraProps, blockType, attributes ) => {
    if ( blockType.name === 'core/gallery' && attributes.isCarousel ) {
        // Adiciona a classe is-carousel ao container da galeria
        extraProps.className = extraProps.className ? `${ extraProps.className } is-carousel` : 'is-carousel';
    }
    return extraProps;
};

addFilter(
    'blocks.getSaveContent.extraProps',
    'meu-projeto/add-carousel-class',
    addCarouselClass
);