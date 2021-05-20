import './required-taxonomies';
import './etiquetador';
import './authors-selector';
import './article-alt-img-meta';
import './article-meta-nota-hermana';
import './article-meta-edicion-impresa';
import './media-popup-photographer';
import './commentary-author-selector';
import './article-meta-participation';
import './image-block-extension';

wp.domReady( () => {
	wp.blocks.unregisterBlockType( 'core/quote' );
    wp.blocks.unregisterBlockType( 'ta/container-with-header' );
} );
