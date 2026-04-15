<?php

namespace App\Http\Controllers;

use App\Models\WhatsappInstance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class WhatsappInstanceController extends Controller
{
    public function index()
    {
        if (! $this->tableExists()) {
            $instances = collect();

            return view('whatsapp.instances.index', compact('instances'))
                ->with('warning', 'A tabela de instâncias do WhatsApp ainda não existe neste ambiente. Rode as migrations para habilitar o módulo.');
        }

        $instances = WhatsappInstance::query()
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get();

        return view('whatsapp.instances.index', compact('instances'));
    }

    public function edit(string $id)
    {
        if (! $this->tableExists()) {
            return redirect()->route('whatsapp.instances.index')
                ->with('error', 'A tabela de instâncias do WhatsApp ainda não existe neste ambiente.');
        }

        $instance = WhatsappInstance::findOrFail($id);

        return view('whatsapp.instances.edit', compact('instance'));
    }

    public function store(Request $request)
    {
        if (! $this->tableExists()) {
            return redirect()->route('whatsapp.instances.index')
                ->with('error', 'A tabela de instâncias do WhatsApp ainda não existe neste ambiente.');
        }

        $request->merge([
            'is_active' => $request->boolean('is_active'),
            'is_default' => $request->boolean('is_default'),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:whatsapp_instances,name',
            'base_url' => 'required|url|max:255',
            'instance_name' => 'required|string|max:255|unique:whatsapp_instances,instance_name',
            'api_key' => 'required|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        if ($validated['is_default'] ?? false) {
            WhatsappInstance::query()->update(['is_default' => false]);
        }

        if (! WhatsappInstance::query()->exists()) {
            $validated['is_default'] = true;
        }

        WhatsappInstance::create($validated);

        return redirect()->route('whatsapp.instances.index')
            ->with('success', 'Instância do WhatsApp cadastrada com sucesso!');
    }

    public function update(Request $request, string $id)
    {
        if (! $this->tableExists()) {
            return redirect()->route('whatsapp.instances.index')
                ->with('error', 'A tabela de instâncias do WhatsApp ainda não existe neste ambiente.');
        }

        $instance = WhatsappInstance::findOrFail($id);

        $request->merge([
            'is_active' => $request->boolean('is_active'),
            'is_default' => $request->boolean('is_default'),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('whatsapp_instances', 'name')->ignore($instance->id)],
            'base_url' => 'required|url|max:255',
            'instance_name' => ['required', 'string', 'max:255', Rule::unique('whatsapp_instances', 'instance_name')->ignore($instance->id)],
            'api_key' => 'required|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        if ($validated['is_default'] ?? false) {
            WhatsappInstance::whereKeyNot($instance->id)->update(['is_default' => false]);
        }

        $instance->update($validated);

        if (! WhatsappInstance::where('is_default', true)->exists()) {
            WhatsappInstance::whereKey($instance->id)->update(['is_default' => true]);
        }

        return redirect()->route('whatsapp.instances.index')
            ->with('success', 'Instância do WhatsApp atualizada com sucesso!');
    }

    public function destroy(string $id)
    {
        if (! $this->tableExists()) {
            return redirect()->route('whatsapp.instances.index')
                ->with('error', 'A tabela de instâncias do WhatsApp ainda não existe neste ambiente.');
        }

        $instance = WhatsappInstance::findOrFail($id);
        $wasDefault = $instance->is_default;

        $instance->delete();

        if ($wasDefault) {
            WhatsappInstance::query()
                ->orderByDesc('is_active')
                ->orderBy('name')
                ->first()
                ?->update(['is_default' => true]);
        }

        return redirect()->route('whatsapp.instances.index')
            ->with('success', 'Instância do WhatsApp removida com sucesso!');
    }

    private function tableExists(): bool
    {
        return Schema::hasTable('whatsapp_instances');
    }
}
