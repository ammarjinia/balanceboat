<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ActiveCampaignService
{
    protected Client $client;
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.active_campaign.api_url'), '/');
        $this->apiKey = config('services.active_campaign.api_key');
        $this->actid = config('services.active_campaign.account_id');
        $this->key = config('services.active_campaign.event_key');
        
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Api-Token' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'timeout' => 30,
        ]);
    }

    /**
     * Sync contact to ActiveCampaign
     */
    public function syncContact(string $email, array $data, array $tags = []): array
    {
        $cacheKey = "ac_contact_sync_{$email}_" . md5(implode(',', $tags));
        
        return Cache::remember($cacheKey, 300, function () use ($email, $data, $tags) {
            try {
                $data = [
                    'contact' => [
                        'email' => $email,
                        'firstName' => $data['firstName'],
                        'lastName'=> $data['lastName'],
                        'phone'=> $data['phone'],
                        'fieldValues' => [
                            [
                                'field' => '1',
                                'value' => isset($data['source']) ? $data['source'] : null 
                            ],
                            [
                                'field' => '2',
                                'value' => isset($data['retreat_interest']) ? $data['retreat_interest'] : null 
                            ],
                            [
                                'field' => '3',
                                'value' => isset($data['preferred_location']) ? $data['preferred_location'] : null 
                            ]    
                        ]
                    ]
                ];

                // Add tags if provided
                if (!empty($tags)) {
                    $data['contact']['tags'] = $tags;
                }

                $response = $this->client->post('/api/3/contact/sync', [
                    'json' => $data
                ]);

                $result = json_decode($response->getBody(), true);
                
                Log::info('ActiveCampaign contact synced', [
                    'email' => $email,
                    'response' => $result
                ]);

                return $result;

            } catch (GuzzleException $e) {
                Log::error('ActiveCampaign contact sync failed', [
                    'email' => $email,
                    'error' => $e->getMessage()
                ]);

                return [
                    'success' => false,
                    'error' => $e->getMessage()
                ];
            }
        });
    }

    /**
     * Track custom event in ActiveCampaign
     */
    /* public function trackEvent(string $event, array $eventData = [], string $email = ''): array
    {
        $curl = curl_init();
        
        $payload = [
            "actid" => $this->actid,
            "key" => $this->key,
            "event" => $event,
            "eventdata" => !empty($eventData) ? json_encode($eventData) : '',
            "visit" => json_encode([
                "email" => $email,
            ]),
        ];

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://trackcmp.net/event",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
        ]);

        $result = curl_exec($curl);
        
        if ($result !== false) {
            $result = json_decode($result);
            
                Log::info(json_encode($result));
            if ($result->success) {
                
                Log::info('ActiveCampaign event tracked successfully', [
                    'event' => $event,
                    'email' => $email,
                ]);
                
                return [
                    'success' => true,
                    'message' => $result->message,
                ];
            } else {
                Log::error('ActiveCampaign event tracking failed', [
                    'event' => $event,
                    'email' => $email,
                    'error' => $result->message,
                ]);
                
                return [
                    'success' => false,
                    'message' => $result->message,
                ];
            }
        } else {
            $error = curl_error($curl);
            
            Log::error('ActiveCampaign cURL request failed', [
                'event' => $event,
                'email' => $email,
                'error' => $error,
            ]);
            
            return [
                'success' => false,
                'message' => 'cURL failed to run: ' . $error,
            ];
        }
        
        curl_close($curl);
    }*/

    public function trackEvent(string $email, string $event, array $data = []): array
    {
        $cacheKey = "ac_event_{$email}_{$event}_" . md5(json_encode($data));
        
        return Cache::remember($cacheKey, 60, function () use ($email, $event, $data) {
            try {
                $payload = [
                    'event' => $event,
                    'email' => $email,
                    'eventdata' => json_encode($data),
                ];

                $response = $this->client->post('/api/3/event', [
                    'json' => $payload
                ]);

                $result = json_decode($response->getBody(), true);
                
                Log::info('ActiveCampaign event tracked', [
                    'email' => $email,
                    'event' => $event,
                    'response' => $result
                ]);

                return $result;

            } catch (GuzzleException $e) {
                Log::error('ActiveCampaign event tracking failed', [
                    'email' => $email,
                    'event' => $event,
                    'error' => $e->getMessage()
                ]);

                return [
                    'success' => false,
                    'error' => $e->getMessage()
                ];
            }
        });
    }

    /**
     * Add tags to contact
     */
    public function addTagsToContact(string $email, array $tags): array
    {
        // First, get the contact ID
        $contact = $this->getContactByEmail($email);
        
        if (empty($contact['contacts'][0]['id'])) {
            return ['success' => false, 'error' => 'Contact not found'];
        }

        $contactId = $contact['contacts'][0]['id'];
        $results = [];

        foreach ($tags as $tag) {
            try {
                // First, get or create the tag
                $tagId = $this->getOrCreateTag($tag);
                
                // Add tag to contact
                $response = $this->client->post('/api/3/contactTags', [
                    'json' => [
                        'contactTag' => [
                            'contact' => $contactId,
                            'tag' => $tagId
                        ]
                    ]
                ]);

                $results[$tag] = json_decode($response->getBody(), true);

            } catch (GuzzleException $e) {
                Log::error('Failed to add tag to contact', [
                    'email' => $email,
                    'tag' => $tag,
                    'error' => $e->getMessage()
                ]);
                $results[$tag] = ['success' => false, 'error' => $e->getMessage()];
            }
        }

        return $results;
    }

    /**
     * Remove all tags from a contact by email
     */
    public function removeAllTagsFromContact(string $email): array
    {
        $contact = $this->getContactByEmail($email);

        if (empty($contact['contacts'][0]['id'])) {
            return ['success' => false, 'error' => 'Contact not found'];
        }

        $contactId = $contact['contacts'][0]['id'];
        $deleted = [];

        try {
            $response = $this->client->get("/api/3/contacts/{$contactId}/contactTags");
            $contactTags = json_decode($response->getBody(), true);

            if (!empty($contactTags['contactTags']) && is_array($contactTags['contactTags'])) {
                foreach ($contactTags['contactTags'] as $ct) {
                    try {
                        $this->client->delete("/api/3/contactTags/{$ct['id']}");
                        $deleted[] = $ct['id'];
                    } catch (GuzzleException $e) {
                        Log::error('Failed to delete contactTag', [
                            'email' => $email,
                            'contactTagId' => $ct['id'],
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }

            return ['success' => true, 'deleted' => $deleted];

        } catch (GuzzleException $e) {
            Log::error('Failed to fetch contact tags for removal', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Remove tags from contact
     */
    public function removeTagsFromContact(string $email, array $tags): array
    {
        $contact = $this->getContactByEmail($email);
        
        if (empty($contact['contacts'][0]['id'])) {
            return ['success' => false, 'error' => 'Contact not found'];
        }

        $contactId = $contact['contacts'][0]['id'];
        $results = [];

        foreach ($tags as $tag) {
            try {
                $tagId = $this->getTagId($tag);
                
                if ($tagId) {
                    // Get contact tag ID first
                    $response = $this->client->get("/api/3/contacts/{$contactId}/contactTags");
                    $contactTags = json_decode($response->getBody(), true);

                    foreach ($contactTags['contactTags'] as $contactTag) {
                        if ($contactTag['tag'] == $tagId) {
                            $this->client->delete("/api/3/contactTags/{$contactTag['id']}");
                            break;
                        }
                    }
                }

                $results[$tag] = ['success' => true];

            } catch (GuzzleException $e) {
                Log::error('Failed to remove tag from contact', [
                    'email' => $email,
                    'tag' => $tag,
                    'error' => $e->getMessage()
                ]);
                $results[$tag] = ['success' => false, 'error' => $e->getMessage()];
            }
        }

        return $results;
    }

    /**
     * Get contact by email
     */
    public function getContactByEmail(string $email): array
    {
        try {
            $response = $this->client->get('/api/3/contacts', [
                'query' => ['email' => $email]
            ]);

            return json_decode($response->getBody(), true);

        } catch (GuzzleException $e) {
            Log::error('Failed to get contact by email', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);

            return ['contacts' => []];
        }
    }

    /**
     * Get or create tag
     */
    protected function getOrCreateTag(string $tagName): int
    {
        $tagId = $this->getTagId($tagName);
        
        if ($tagId) {
            return $tagId;
        }

        // Create new tag
        $response = $this->client->post('/api/3/tags', [
            'json' => [
                'tag' => [
                    'tag' => $tagName,
                    'tagType' => 'contact',
                    'description' => "Auto-generated tag: {$tagName}"
                ]
            ]
        ]);

        $result = json_decode($response->getBody(), true);
        return $result['tag']['id'];
    }

    /**
     * Get tag ID by name
     */
    protected function getTagId(string $tagName): ?int
    {
        try {
            $response = $this->client->get('/api/3/tags', [
                'query' => ['search' => $tagName]
            ]);

            $tags = json_decode($response->getBody(), true);

            foreach ($tags['tags'] as $tag) {
                if ($tag['tag'] === $tagName) {
                    return $tag['id'];
                }
            }

            return null;

        } catch (GuzzleException $e) {
            return null;
        }
    }

    /**
     * Helper method for cart tracking
     */
    public function trackCartActivity(string $email, array $data, array $tags = ['Added to Cart']): array
    {
        $contactResult = $this->syncContact($email, $data, $tags);
        $eventResult = $this->trackEvent($email, 'added_to_cart', $data);

        return [
            'contact_sync' => $contactResult,
            'event_track' => $eventResult
        ];
    }

    /**
     * Helper method for booking completion
     */
    public function trackBookingCompletion(string $email, string $data, string $retreatName): array
    {
        $contactResult = $this->syncContact($email, $data, ['Booking Complete']);
        $eventResult = $this->trackEvent($email, 'booking_complete', [
            'retreat' => $retreatName
        ]);

        // Remove abandoned cart tag if exists
        $this->removeTagsFromContact($email, ['Added to Cart']);

        return [
            'contact_sync' => $contactResult,
            'event_track' => $eventResult
        ];
    }
}