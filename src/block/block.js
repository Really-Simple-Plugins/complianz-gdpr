/**
 * BLOCK:Complianz Documents block
 *
 * Registering the Complianz Privacy Suite documents block with Gutenberg.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';

import * as api from './utils/api';
//
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.editor;
const { SelectControl } = wp.components;
const { Component } = wp.element;
const el = wp.element.createElement;

    class selectDocument extends Component {
        // Method for setting the initial state.
        static getInitialState(selectedDocument) {
            return {
                posts: [],
                selectedDocument: selectedDocument,
                post: {},
                hasDocuments: true,
            };
        }

        // Constructing our component. With super() we are setting everything to 'this'.
        // Now we can access the attributes with this.props.attributes
        constructor() {
            super(...arguments);
            // Maybe we have a previously selected post. Try to load it.
            this.state = this.constructor.getInitialState(this.props.attributes.selectedDocument);

            // Bind so we can use 'this' inside the method.
            this.getPosts = this.getPosts.bind(this);
            this.getPosts();

            this.onChangeSelectPost = this.onChangeSelectPost.bind(this);
        }

        getPosts(args = {}) {
            return (api.getDocuments()).then( ( response ) => {
                let posts = response.data;
                if( posts && 0 !== this.state.selectedDocument ) {
                    // If we have a selected Post, find that post and add it.
                    const post = posts.find( ( item ) => { return item.id == this.state.selectedDocument } );

                    console.log('set state false');
                    if (posts.length === 0) {
                        this.setState({hasDocuments: false});

                        this.props.setAttributes({
                            hasDocuments: false,
                        });
                    }

                    // This is the same as { post: post, posts: posts }
                    //this.state.posts = posts;
                    this.setState( { post, posts } );
                } else {
                    //this.state.posts = posts;
                    this.setState({ posts });
                }
            });
        }

        onChangeSelectPost(value) {
            const post = this.state.posts.find((item) => {
                return item.id === value
            });

            // Set the state
            this.setState({selectedDocument: value, post});

            // Set the attributes
            this.props.setAttributes({
                selectedDocument: value,
            });

        }

        render() {
            const { className, attributes: {} = {} } = this.props;

            let options = [{value: 0, label: __('Select a document')}];
            let output = __('Loading...');
            let title = __('document-title');

            if (!this.props.attributes.hasDocuments){
                output = __('No documents found. Please finish the Complianz Privacy Suite to generate documents');
                title = 'no-documents';
            }

            //build options
            if (this.state.posts.length > 0) {
                if (!this.props.isSelected){
                    output = __('Click this block to show the options');
                } else {
                    output = __('Select a document type from the dropdownlist');
                }
                this.state.posts.forEach((post) => {
                    options.push({value: post.id, label: post.title});
                });
            }

            //load content
            if (this.props.attributes.selectedDocument!==0 && this.state.post && this.state.post.hasOwnProperty('title')) {
                output = this.state.post.content;
                title = this.state.post.title;
            }

            return [
                !!this.props.isSelected && (
                    <InspectorControls key='inspector'>
                        <SelectControl onChange={this.onChangeSelectPost} value={this.props.attributes.selectedDocument} label={__('Select a document')}
                                       options={options}/>
                    </InspectorControls>
                ),
                <div key={title} className={className} dangerouslySetInnerHTML={ { __html: output } }></div>
            ]
        }

    }

    /**
     * Register: a Gutenberg Block.
     *
     * Registers a new block provided a unique name and an object defining its
     * behavior. Once registered, the block is made editor as an option to any
     * editor interface where blocks are implemented.
     *
     * @link https://wordpress.org/gutenberg/handbook/block-api/
     * @param  {string}   name     Block name.
     * @param  {Object}   settings Block settings.
     * @return {?WPBlock}          The block, if it has been successfully
     *                             registered; otherwise `undefined`.
     */

    const iconEl =
        el('svg', { width: 20, height: 20 ,viewBox : "0 0 133.62 133.62"},
        //el('path', { d: "M12.5,12H12v-0.5c0-0.3-0.2-0.5-0.5-0.5H11V6h1l1-2c-1,0.1-2,0.1-3,0C9.2,3.4,8.6,2.8,8,2V1.5C8,1.2,7.8,1,7.5,1 S7,1.2,7,1.5V2C6.4,2.8,5.8,3.4,5,4C4,4.1,3,4.1,2,4l1,2h1v5c0,0-0.5,0-0.5,0C3.2,11,3,11.2,3,11.5V12H2.5C2.2,12,2,12.2,2,12.5V13 h11v-0.5C13,12.2,12.8,12,12.5,12z M7,11H5V6h2V11z M10,11H8V6h2V11z" } )
        el('path', { d: "M113.63,19.34C100.37,6.51,84.41,0,66.2,0A64.08,64.08,0,0,0,19.36,19.36,64.08,64.08,0,0,0,0,66.2c0,18.25,6.51,34.21,19.34,47.43s28.61,20,46.86,20,34.2-6.72,47.45-20,20-29.21,20-47.45S126.89,32.21,113.63,19.34Zm-2.85,91.44c-12.47,12.46-27.47,18.77-44.58,18.77s-31.89-6.31-43.94-18.75A62.11,62.11,0,0,1,4.07,66.2a60.14,60.14,0,0,1,18.17-44,60.1,60.1,0,0,1,44-18.17c17.12,0,32.12,6.12,44.6,18.19s18.75,26.86,18.75,43.94S123.23,98.32,110.78,110.78Z" } ),
        el('path', { d: "M99.49,30.71a6.6,6.6,0,0,0-9.31,0L40.89,80,35.3,74.41a6.58,6.58,0,0,0-9.31,0l-2.12,2.12a6.6,6.6,0,0,0,0,9.31l9.64,9.64a6.67,6.67,0,0,0,.56.65l2.12,2.12L41,102.8l4-4a8.39,8.39,0,0,0,.65-.56l2.12-2.12a8.39,8.39,0,0,0,.56-.65l53.34-53.34a6.6,6.6,0,0,0,0-9.31Z" } ),
        el('path', { d: "M94.91,86.63H65.15L48.86,102.8H94.91a6.6,6.6,0,0,0,6.58-6.58v-3A6.61,6.61,0,0,0,94.91,86.63Z" } ),
        el('path', { d: "M47.09,45H68.71L85,28.79H47.09a6.6,6.6,0,0,0-6.58,6.58v3A6.6,6.6,0,0,0,47.09,45Z" } ),

    //     " style="fill: #1d1d1b"/>
    // <path d="" style="fill: #1d1d1b"/>
    // <path d="" style="fill: #1d1d1b"/>
    // <path d="" style="fill: #1d1d1b"/>
    );

    registerBlockType('complianz/document', {
        title: __('Legal document - Complianz'), // Block title.
        icon: iconEl, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
        category: 'widgets',
        keywords: [
            __('privacy statement'),
            __('cookie statement'),
            __('disclaimer'),
        ],
        //className: 'cmplz-document',
        attributes: {
            hasDocuments: {
                type: 'string',
                default: 'false',
            },
            content: {
                type: 'string',
                source: 'children',
                selector: 'p',
            },
            title: {
                type: 'string',
                selector: 'h2'
            },
            selectedDocument: {
                type: 'string',
                default: '',
            },
            posts: {
                type: 'array',
            },
            post: {
                type: 'array',
            }
        },
        /**
         * The edit function describes the structure of your block in the context of the editor.
         * This represents what the editor will render when the block is used.
         *
         * The "edit" property must be a valid function.
         *
         * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
         */

        edit:selectDocument,

        /**
         * The save function defines the way in which the different attributes should be combined
         * into the final markup, which is then serialized by Gutenberg into post_content.
         *
         * The "save" property must be specified and must be a valid function.
         *
         * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
         */

        save: function() {
            // Rendering in PHP
            return null;
        },
    });
