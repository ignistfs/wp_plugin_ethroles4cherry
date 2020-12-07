jQuery( function( $ ) {
    $( '#ethpress_token_roles_add_new' ).click( function( e ) {
        var node;
        e.preventDefault();
        node = $( '#ethpress_token_roles_contracts_container > input:last-child' ).clone();
        node.attr( 'id', '' );
        node.attr( 'value', '' );
        node.attr( 'name', 'ethpress_token_roles[contract_address_' + Math.random() + ']' );

        $( '#ethpress_token_roles_contracts_container' ).append( node );
    });
});
