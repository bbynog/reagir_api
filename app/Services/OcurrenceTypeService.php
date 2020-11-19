<?php

namespace App\Services;

use App\Models\OcurrenceType;

class OcurrenceTypeService
{
    private $ocurrence_type;

    public function __construct(OcurrenceType $ocurrence_type)
    {
        $this->ocurrence_type = $ocurrence_type;
    }

    public function save(array $data)
    {
        $this->ocurrence_type->name = $data['name'];
        # Gostei da maneira que você utilizou esse strotolower para unificar/padronizar o formato do valor dentro do banco, tá de parabas! <3
        # Não compreendi a lógica de você utilizar essa estrutura do switch.
        # acredito que apenas um $this->ocurrency_type->status = strtolower($data['status']); resolveria seu problema aqui.


        # O Switch funciona como um grande IF, aonde o valor '$variavel' dentro do switch($variavel) vai ser condicionado a cada case.
        # os 'case' que vc escreve ditam o conjunto de regras que você vai condicionar se o valor for o que está ali no case, por exemplo 'leve' ou 'média', etc.


        # PS: Esses comentários acima servem pra o update também.
        switch (strtolower($data['status'])) {
            case 'leve':
            case 'média':
            case 'media':
            case 'pesada':
                # Nesse contexto que você escreveu, você só está salvando/criando um ocurrence_type se o status for igual a 'pesada'.
                $this->ocurrence_type->status = $data['status'];
                $this->ocurrence_type->save();
                # Perceba que essas 2 linhas de código acima são o que você precisa pra salvar
                # o seu objeto ocurrency_type e você tá condicionando seu save inteiro a se o status for 'pesada'

                return $this->ocurrence_type;
                break;
            default:
                # Os retornos do tipo 'response' são feitos apenas no controlador,
                # que é quem envia uma resposta a sua requisição contendo um valor e um status code.
                # Nesse caso aqui seria interessante que você apenas retornasse o objeto que foi salvo para que seu controlador informe
                # o que foi salvo no banco (o que foi salvo no banco fica guardado dentro de $this->ocurrency_type
                # logo após você ter usado o o método ->save().
                return response("Tipo inexistente", 400);
        }
    }

    public function update(array $data, $id)
    {
        $ocurrence_type = $this->ocurrence_type->find($id);
        switch (strtolower($data['status'])) {
            case 'leve':
            case 'média':
            case 'media':
            case 'pesada':
                $ocurrence_type->update($data);


                return $ocurrence_type;
                break;
            default:
                # Os retornos do tipo 'response' são feitos apenas no controlador,
                # que é quem envia uma resposta a sua requisição contendo um valor e um status code.
                # Nesse caso aqui seria interessante que você apenas retornasse o objeto que foi salvo para que seu controlador informe
                # o que foi salvo no banco (o que foi salvo no banco fica guardado dentro de $ocurrence_type
                # logo após você ter usado o o método ->update().
                return response("Tipo inexistente", 400);
        }
    }

    public function delete($id)
    {
        $ocurrence_type = $this->ocurrence_type->find($id);
        $deleted = $ocurrence_type->delete();

        #Dica: Você poderia retornar aqui o $ocurrency_type->delete() diretamente sem precisar criar a variável '$deleted',
        # pois esse método delete() retorna true (se deletou) ou false (se não deletou) e é o que basicamente vc mostra na resposta na sua action do controlador.
        return $deleted;
    }

    public function list()
    {
        return $this->ocurrence_type->all();
    }

    public function show($id)
    {
        return $this->ocurrence_type->find($id);
    }

    public function status(array $data, $id)
    {
        $ocurrence_type = $this->ocurrence_type->find($id);
        switch (strtolower($data['status'])) {
            case 'leve':
            case 'média':
            case 'media':
            case 'pesada':
                $ocurrence_type->status = $data['status'];
                $ocurrence_type->save();

                return $ocurrence_type;
                break;
            default:
                return response("Tipo inexistente", 400);
        }
    }


}
