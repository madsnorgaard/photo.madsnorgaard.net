/**
 * Gutenberg Blocks
 */

const { registerBlockType } = wp.blocks;

import './gallery/index.js';
import { settings } from './gallery/index';
registerBlockType( `mauer-stills/gallery`, { category: "common", ...settings } );