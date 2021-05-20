import {
    assign
} from 'lodash';
const { createHigherOrderComponent } = wp.compose;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, ToggleControl } = wp.components;

// ATTRIBUTES

wp.hooks.addFilter(
	'blocks.registerBlockType',
	'ta/image-block-photographer-attr',
	settings => {
		if(settings.name != 'core/image')
			return settings;

		settings.attributes = {
			...settings.attributes,
			showPhotographer: {
				type: 'boolean',
				default: '',
			},
		};

		return settings;
	}
);


// EDIT
const TAExtendedImageBlock = (props) => {
	const { BlockEdit, setAttributes, attributes } = props;
	const { showPhotographer, id } = attributes;

	return (
		<>
            <InspectorControls>
                <PanelBody title="Fotógrafo" icon={ "admin-users" } initialOpen={ true }>
                    <ToggleControl
                        label="Mostrar datos del fotógrafo"
                        checked={ showPhotographer }
                        onChange={ () => setAttributes({ showPhotographer: !showPhotographer }) }
                    />
                </PanelBody>
            </InspectorControls>
			<BlockEdit { ...props } />
		</>
	)
}

const withInspectorControls = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
		const { name } = props;
		if(name != 'core/image')
			return <BlockEdit {...props}/>;

        return <TAExtendedImageBlock BlockEdit = {BlockEdit} {...props} />;
    };
}, 'withInspectorControl' );


wp.hooks.addFilter(
    'editor.BlockEdit',
    'ta/image-block-photographer',
    withInspectorControls
);


// SAVE
function addBackgroundColorStyle( props, blockType, attributes ) {
	const { showPhotographer = false } = attributes;

	if(blockType.name != 'core/image'){
		return props;
	}

    return assign( props, { showPhotographer: showPhotographer } );
}

wp.hooks.addFilter(
    'blocks.getSaveContent.extraProps',
    'ta/save-image-block-photoghapher-attr',
    addBackgroundColorStyle
);
