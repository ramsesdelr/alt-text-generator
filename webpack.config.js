const { watch } = require('fs');
const path = require('path');

module.exports = {
    entry: './src/index.ts', // Entry point of your TypeScript file
    module: {
        rules: [
            {
                test: /\.ts$/,
                use: 'ts-loader',
                exclude: /node_modules/,
            },
        ],
    },
    resolve: {
        extensions: ['.ts', '.js'], // Resolve TypeScript and JavaScript files
    },
    output: {
        filename: 'bundle.js', // The bundled output file
        path: path.resolve(__dirname, 'dist'),
    },
    watch: true, //Enable webpack to watch for changes.
};
