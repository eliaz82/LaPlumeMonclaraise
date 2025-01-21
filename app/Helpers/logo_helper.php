<?php

function getAssociationLogo()
{
    $associationModel = model('Association');
    
    $association = $associationModel->find(1);

    return $association['logo'];
}